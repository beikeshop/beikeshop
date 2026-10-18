<?php

namespace Beike\Admin\Services;

use Beike\Models\Order;
use Beike\Models\OrderProduct;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * 生成订单商品明细 Word 文档。
 *
 * 文档使用最小 OOXML 包生成，避免为后台导出功能增加新的 Composer 依赖。
 */
class OrderWordExportService
{
    private const WORD_NS = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    private const REL_NS = 'http://schemas.openxmlformats.org/package/2006/relationships';

    private const DOC_REL_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const IMAGE_REL_TYPE = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image';

    /**
     * 生成临时 docx 文件并返回路径。
     */
    public function create(Order $order): string
    {
        $order->loadMissing([
            'orderProducts.productSku',
            'orderProducts.product.descriptions',
        ]);

        $products       = $order->orderProducts;
        $customizations = $this->customizations($products);
        $images         = [];
        $body           = $this->recipient($order);

        foreach ($products as $index => $product) {
            $image = $this->image($product->image ?: $product->productSku?->image ?: $product->product?->image);
            if ($image) {
                $images[] = $image;
            }

            $body .= $this->productBlock(
                $product,
                $customizations[(int) $product->id] ?? [],
                $image,
                count($images)
            );

            if ($index < $products->count() - 1) {
                $body .= $this->spacerParagraph();
            }
        }

        $body .= '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440"/></w:sectPr>';

        $fileName = 'order-' . preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) $order->number) . '-items.docx';
        $filePath = storage_path('app/' . $fileName);
        $zip      = new \ZipArchive;
        if ($zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('无法创建 Word 文档。');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->rootRelationships());
        $zip->addFromString('word/document.xml', $this->document($body));

        $documentRelationships = '<Relationships xmlns="' . self::REL_NS . '">';
        foreach ($images as $index => $image) {
            $relationshipId = 'rId' . ($index + 2);
            $documentRelationships .= '<Relationship Id="' . $relationshipId . '" Type="' . self::IMAGE_REL_TYPE . '" Target="media/image' . ($index + 1) . '.' . $image['extension'] . '"/>';
            $zip->addFromString('word/media/image' . ($index + 1) . '.' . $image['extension'], $image['data']);
        }
        $documentRelationships .= '</Relationships>';
        $zip->addFromString('word/_rels/document.xml.rels', $documentRelationships);
        $zip->close();

        return $filePath;
    }

    private function recipient(Order $order): string
    {
        $name         = $order->shipping_customer_name ?: ($order->customer_name ?: $order->payment_customer_name);
        $telephone    = $order->shipping_telephone ?: ($order->telephone ?: $order->payment_telephone);
        $addressParts = [
            $order->shipping_address_1,
            $order->shipping_address_2,
            $order->shipping_city,
            $order->shipping_zone,
            $order->shipping_zipcode,
            $order->shipping_country,
        ];
        $address = implode(', ', array_values(array_filter(array_map(
            static fn ($value): string => trim((string) $value),
            $addressParts
        ), static fn (string $value): bool => $value !== '')));

        if ($address === '') {
            $addressParts = [
                $order->payment_address_1,
                $order->payment_address_2,
                $order->payment_city,
                $order->payment_zone,
                $order->payment_zipcode,
                $order->payment_country,
            ];
            $address = implode(', ', array_values(array_filter(array_map(
                static fn ($value): string => trim((string) $value),
                $addressParts
            ), static fn (string $value): bool => $value !== '')));
        }

        return $this->fieldParagraph('名字', (string) $name, 22)
            . $this->fieldParagraph('地址', $address, 22)
            . $this->fieldParagraph('电话', (string) $telephone, 22)
            . $this->spacerParagraph(120);
    }

    /**
     * 生成模板中的“左侧图片、右侧一行字段”商品块。
     *
     * @param array<int, array{label: string, value: string}> $customizations
     */
    private function productBlock(OrderProduct $product, array $customizations, ?array $image, int $imageCount): string
    {
        $customizationValues = [];
        foreach ($customizations as $customization) {
            $label = $this->customizationLabel((string) ($customization['label'] ?? ''));
            if ($label !== '') {
                $customizationValues[$label] = (string) ($customization['value'] ?? '');
            }
        }

        $imageMarkup = $image
            ? $this->imageParagraph('rId' . ($imageCount + 1), $imageCount, $image['width'], $image['height'])
            : $this->emptyParagraph();

        $fields = [
            ['数量', (string) ((int) $product->quantity)],
            ['尺码', $this->size($product)],
        ];

        foreach (['定制号码' => '号码', '定制名称' => '名称'] as $key => $label) {
            $value = trim($customizationValues[$key] ?? '');
            if ($value !== '') {
                $fields[] = [$label, $value];
            }
        }

        $fields[] = ['类别', $this->category($product)];

        return '<w:tbl><w:tblPr><w:tblW w:w="9026" w:type="dxa"/><w:tblLayout w:type="fixed"/><w:tblCellMar><w:top w:w="0" w:type="dxa"/><w:start w:w="0" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:end w:w="0" w:type="dxa"/></w:tblCellMar><w:tblBorders><w:top w:val="nil"/><w:left w:val="nil"/><w:bottom w:val="nil"/><w:right w:val="nil"/><w:insideH w:val="nil"/><w:insideV w:val="nil"/></w:tblBorders></w:tblPr><w:tblGrid><w:gridCol w:w="2900"/><w:gridCol w:w="6126"/></w:tblGrid><w:tr><w:tc><w:tcPr><w:tcW w:w="2900" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>' . $imageMarkup . '</w:tc><w:tc><w:tcPr><w:tcW w:w="6126" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>' . $this->productLine($fields) . '</w:tc></w:tr></w:tbl>';
    }

    private function category(OrderProduct $product): string
    {
        $name = $this->englishProductName($product);
        if ($name === '') {
            return '待处理';
        }

        $normalized = mb_strtolower(str_replace(['’', '‘', '＇'], "'", $name));
        $matches    = [];
        if (preg_match('/(?:^|[^a-z])youth(?:$|[^a-z])|童装|儿童|婴儿|幼儿|男童|女童/iu', $normalized)) {
            $matches['童装'] = true;
        }
        if (preg_match("/(?:^|[^a-z])women(?:'s|s)?(?:$|[^a-z])|女装|女士|女款|女式/iu", $normalized)) {
            $matches['女装'] = true;
        }
        if (preg_match("/(?:^|[^a-z])men(?:'s|s)?(?:$|[^a-z])|男装|男士|男款|男式/iu", $normalized)) {
            $matches['男装'] = true;
        }

        if (count($matches) !== 1) {
            return '待处理';
        }

        return (string) array_key_first($matches);
    }

    private function englishProductName(OrderProduct $product): string
    {
        foreach ($product->product?->descriptions ?? [] as $description) {
            if (strtolower((string) $description->locale) === 'en') {
                return trim((string) $description->name);
            }
        }

        return trim((string) $product->name);
    }

    /**
     * @param iterable<OrderProduct> $products
     * @return array<int, array<int, array{label: string, value: string}>>
     */
    private function customizations(iterable $products): array
    {
        $ids = [];
        foreach ($products as $product) {
            $ids[] = (int) $product->id;
        }
        if (! $ids || ! class_exists(\Plugin\ProductCustomization\Models\OrderCustomization::class)) {
            return [];
        }

        try {
            if (! Schema::hasTable('order_product_customizations')) {
                return [];
            }

            $models = \Plugin\ProductCustomization\Models\OrderCustomization::query()
                ->whereIn('order_product_id', $ids)
                ->get();
        } catch (Throwable) {
            return [];
        }

        $result = [];
        foreach ($models as $model) {
            $fields = is_array($model->fields) ? $model->fields : [];
            $values = is_array($model->values) ? $model->values : [];
            $items  = [];
            foreach ($fields as $field) {
                $key   = (string) ($field['key'] ?? $field['field_key'] ?? '');
                $value = trim((string) ($values[$key] ?? ''));
                if ($value === '') {
                    continue;
                }
                $options = $field['options'] ?? [];
                if (is_array($options) && ! array_is_list($options) && isset($options[$value])) {
                    $value = (string) $options[$value];
                }
                $label = $this->customizationLabel((string) ($field['label'] ?? $key), $key);
                if (! in_array($label, ['定制号码', '定制名称'], true)) {
                    continue;
                }
                $items[] = [
                    'label' => $label,
                    'value' => $value,
                ];
            }
            if ($items) {
                $priority = ['定制号码' => 0, '定制名称' => 1];
                usort($items, fn (array $first, array $second): int => ($priority[$this->customizationLabel($first['label'])] ?? 2)
                    <=> ($priority[$this->customizationLabel($second['label'])] ?? 2));
                $result[(int) $model->order_product_id] = $items;
            }
        }

        return $result;
    }

    private function size(OrderProduct $product): string
    {
        $labels = [(string) $product->name];

        try {
            $label = $product->productSku?->getVariantLabel();
            if ($label) {
                $labels[] = trim($label);
            }
        } catch (Throwable) {
            // 历史 SKU 或已删除商品可能无法重新解析规格，继续使用订单名称快照。
        }

        foreach ($labels as $label) {
            if (preg_match('/(?:尺码|尺寸|服装尺码|大小|size)\s*[:：\-]?\s*([^;；|,，]+)/iu', $label, $matches)) {
                return trim($matches[1]);
            }
        }

        return '-';
    }

    private function customizationLabel(string $label, string $key = ''): string
    {
        $text = trim($label . ' ' . $key);
        if (preg_match('/号码|number|num(?:ber)?|shirt[_\s-]*(?:no|number)|jersey[_\s-]*(?:no|number)/iu', $text)) {
            return '定制号码';
        }
        if (preg_match('/名字|姓名|名称|name|player[_\s-]*name|jersey[_\s-]*name/iu', $text)) {
            return '定制名称';
        }

        return '';
    }

    private function image(mixed $image): ?array
    {
        if (is_array($image)) {
            $image = $this->imageValue($image);
        }
        if (is_string($image) && in_array(substr(trim($image), 0, 1), ['[', '{'], true)) {
            $decoded = json_decode($image, true);
            if (is_array($decoded)) {
                $image = $this->imageValue($decoded);
            }
        }
        $image = trim((string) $image);
        if ($image === '') {
            return null;
        }

        $remoteUrl = preg_match('#^https?://#i', $image) ? $image : null;
        if ($remoteUrl) {
            $image = (string) parse_url($remoteUrl, PHP_URL_PATH);
        }
        $image           = urldecode((string) preg_replace('/[?#].*$/', '', $image));
        $relative        = ltrim(str_replace('\\', '/', $image), '/');
        $relativeStorage = preg_replace('#^storage/#i', '', $relative);

        $candidates = [
            $image,
            public_path($relative),
            storage_path('app/public/' . $relativeStorage),
        ];
        $roots = array_filter([
            realpath(public_path()),
            realpath(storage_path('app/public')),
        ]);
        $path = null;
        foreach ($candidates as $candidate) {
            $candidate = realpath($candidate);
            if (! $candidate || ! is_file($candidate)) {
                continue;
            }
            foreach ($roots as $root) {
                if (str_starts_with($candidate, $root . DIRECTORY_SEPARATOR)) {
                    $path = $candidate;

                    break 2;
                }
            }
        }

        if ($path) {
            $data = @file_get_contents($path);
            if ($data !== false) {
                return $this->imageData($data);
            }
        }

        if ($remoteUrl) {
            try {
                $response = Http::timeout(10)->get($remoteUrl);
                if ($response->successful()) {
                    return $this->imageData($response->body());
                }
            } catch (Throwable) {
                // 远程图片不可访问时跳过图片，不能阻止订单明细导出。
            }
        }

        return null;
    }

    private function imageData(string $data): ?array
    {
        $info = @getimagesizefromstring($data);
        if (! $info || empty($info[0]) || empty($info[1])) {
            return null;
        }
        $mime = $info['mime'] ?? '';

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'png',
            default      => null,
        };
        if (! $extension) {
            return null;
        }

        if ($mime === 'image/webp') {
            $data = $this->convertToPng($data);
            if ($data === null) {
                return null;
            }
            $info      = @getimagesizefromstring($data) ?: $info;
            $extension = 'png';
        }

        $maxWidth  = 1737360;
        $maxHeight = 1600200;
        $scale     = min($maxWidth / ($info[0] * 9525), $maxHeight / ($info[1] * 9525), 1);

        return [
            'data'      => $data,
            'extension' => $extension,
            'width'     => (int) round($info[0] * 9525 * $scale),
            'height'    => (int) round($info[1] * 9525 * $scale),
        ];
    }

    private function convertToPng(string $data): ?string
    {
        if (function_exists('imagecreatefromstring')) {
            $source = @imagecreatefromstring($data);
            if ($source) {
                imagealphablending($source, false);
                imagesavealpha($source, true);
                ob_start();
                imagepng($source);
                $converted = ob_get_clean();
                imagedestroy($source);
                if (is_string($converted) && $converted !== '') {
                    return $converted;
                }
            }
        }

        try {
            $converted = (string) \Intervention\Image\Facades\Image::make($data)->encode('png');

            return $converted !== '' ? $converted : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function imageValue(array $image): mixed
    {
        foreach (['src', 'url', 'path', 'value'] as $key) {
            if (isset($image[$key])) {
                return $image[$key];
            }
        }

        $first = $image[0] ?? '';

        return is_array($first) ? $this->imageValue($first) : $first;
    }

    /**
     * @param array<int, array{0: string, 1: string}> $fields
     */
    private function productLine(array $fields): string
    {
        $runs = '';
        foreach ($fields as $field) {
            $runs .= $this->inlineField($field[0], $field[1]);
        }

        return '<w:p><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/></w:pPr>' . $runs . '</w:p>';
    }

    private function inlineField(string $label, string $value): string
    {
        $font = '<w:rFonts w:ascii="Microsoft YaHei" w:hAnsi="Microsoft YaHei" w:eastAsia="Microsoft YaHei"/><w:sz w:val="22"/>';

        return '<w:r><w:rPr>' . $font . '<w:b/></w:rPr><w:t xml:space="preserve">' . $this->xml($label . '：') . '</w:t></w:r><w:r><w:rPr>' . $font . '</w:rPr><w:t xml:space="preserve">' . $this->xml($this->text($value) . ' ') . '</w:t></w:r>';
    }

    private function paragraph(string $text, bool $bold = false, int $size = 22): string
    {
        $properties = '<w:rPr><w:rFonts w:ascii="Microsoft YaHei" w:hAnsi="Microsoft YaHei" w:eastAsia="Microsoft YaHei"/><w:sz w:val="' . $size . '"/>' . ($bold ? '<w:b/>' : '') . '</w:rPr>';

        return '<w:p><w:pPr><w:spacing w:after="80"/></w:pPr><w:r>' . $properties . '<w:t xml:space="preserve">' . $this->xml($text) . '</w:t></w:r></w:p>';
    }

    private function fieldParagraph(string $label, string $value, int $size = 22): string
    {
        $font = '<w:rFonts w:ascii="Microsoft YaHei" w:hAnsi="Microsoft YaHei" w:eastAsia="Microsoft YaHei"/><w:sz w:val="' . $size . '"/>';

        return '<w:p><w:pPr><w:spacing w:after="80"/></w:pPr><w:r><w:rPr>' . $font . '<w:b/></w:rPr><w:t xml:space="preserve">' . $this->xml($label . '：') . '</w:t></w:r><w:r><w:rPr>' . $font . '</w:rPr><w:t xml:space="preserve">' . $this->xml($this->text($value)) . '</w:t></w:r></w:p>';
    }

    private function emptyParagraph(): string
    {
        return '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr></w:p>';
    }

    private function spacerParagraph(int $after = 80): string
    {
        return '<w:p><w:pPr><w:spacing w:after="' . $after . '"/></w:pPr></w:p>';
    }

    private function pageBreak(): string
    {
        return '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
    }

    private function imageParagraph(string $relationshipId, int $index, int $width, int $height): string
    {
        return '<w:p><w:r><w:drawing><wp:inline xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" distT="0" distB="0" distL="0" distR="0"><wp:extent cx="' . $width . '" cy="' . $height . '"/><wp:docPr id="' . $index . '" name="Product image ' . $index . '"/><a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:nvPicPr><pic:cNvPr id="' . $index . '" name="image' . $index . '"/><pic:cNvPicPr/></pic:nvPicPr><pic:blipFill><a:blip r:embed="' . $relationshipId . '" xmlns:r="' . self::DOC_REL_NS . '"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill><pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="' . $width . '" cy="' . $height . '"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr></pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing></w:r></w:p>';
    }

    private function document(string $body): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="' . self::WORD_NS . '"><w:body>' . $body . '</w:body></w:document>';
    }

    private function contentTypes(): string
    {
        $types = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Default Extension="jpg" ContentType="image/jpeg"/><Default Extension="jpeg" ContentType="image/jpeg"/><Default Extension="png" ContentType="image/png"/><Default Extension="gif" ContentType="image/gif"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>';

        return $types . '</Types>';
    }

    private function rootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="' . self::REL_NS . '"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>';
    }

    private function text(mixed $value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '-' : $value;
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
