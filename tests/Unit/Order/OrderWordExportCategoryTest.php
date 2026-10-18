<?php

namespace Tests\Unit\Order;

use Beike\Admin\Services\OrderWordExportService;
use Beike\Models\OrderProduct;
use Beike\Models\Product;
use Beike\Models\ProductDescription;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class OrderWordExportCategoryTest extends TestCase
{
    public function test_english_product_name_maps_to_one_category(): void
    {
        $this->assertSame('男装', $this->classify("Men's NFL Jersey"));
        $this->assertSame('女装', $this->classify("Women's NBA Jersey"));
        $this->assertSame('童装', $this->classify('Youth NHL Jersey'));
    }

    public function test_curly_apostrophe_is_supported(): void
    {
        $this->assertSame('女装', $this->classify('Women’s Custom Jersey'));
    }

    public function test_multiple_or_missing_keywords_need_processing(): void
    {
        $this->assertSame('待处理', $this->classify("Men's Women's Jersey"));
        $this->assertSame('待处理', $this->classify('Custom Accessory'));
    }

    public function test_english_description_is_used_before_order_snapshot(): void
    {
        $this->assertSame('男装', $this->classify('中文订单快照', "Men's Jersey"));
    }

    public function test_customization_fields_are_omitted_when_values_are_missing(): void
    {
        $xml = $this->productBlock([]);

        $this->assertStringNotContainsString('号码：', $xml);
        $this->assertStringNotContainsString('名称：', $xml);
    }

    public function test_customization_fields_are_included_when_values_exist(): void
    {
        $xml = $this->productBlock([
            ['label' => '定制号码', 'value' => '12'],
            ['label' => '定制名称', 'value' => 'PLAYER'],
        ]);

        $this->assertStringContainsString('号码：', $xml);
        $this->assertStringContainsString('12', $xml);
        $this->assertStringContainsString('名称：', $xml);
        $this->assertStringContainsString('PLAYER', $xml);
    }

    private function classify(string $snapshotName, string $englishName = null): string
    {
        $product = $this->model(Product::class);
        if ($englishName !== null) {
            $description = $this->model(ProductDescription::class);
            $description->setRawAttributes(['locale' => 'en', 'name' => $englishName]);
            $product->setRelation('descriptions', collect([$description]));
        } else {
            $product->setRelation('descriptions', collect());
        }

        $orderProduct = $this->model(OrderProduct::class);
        $orderProduct->setRawAttributes(['name' => $snapshotName]);
        $orderProduct->setRelation('product', $product);

        $method = new ReflectionMethod(OrderWordExportService::class, 'category');

        return $method->invoke(new OrderWordExportService, $orderProduct);
    }

    private function productBlock(array $customizations): string
    {
        $orderProduct = $this->model(OrderProduct::class);
        $orderProduct->setRawAttributes(['name' => 'Size: M', 'quantity' => 1]);
        $orderProduct->setRelation('product', null);
        $orderProduct->setRelation('productSku', null);

        $method = new ReflectionMethod(OrderWordExportService::class, 'productBlock');

        return $method->invoke(new OrderWordExportService, $orderProduct, $customizations, null, 0);
    }

    private function model(string $class): object
    {
        return (new ReflectionClass($class))->newInstanceWithoutConstructor();
    }
}
