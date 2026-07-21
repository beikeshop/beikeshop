<?php

namespace Beike\Admin\Services;

use Beike\Models\Product;
use Beike\Models\ProductSku;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        $product = new Product;

        return $this->createOrUpdate($product, $data);
    }

    public function update(Product $product, array $data): Product
    {
        return $this->createOrUpdate($product, $data);
    }

    protected function createOrUpdate(Product $product, array $data): Product
    {
        $isUpdating = $product->id > 0;
        foreach ($data['skus'] as $sku) {
            $productSku = ProductSku::query()->where('sku', $sku['sku'])->first();
            if ($productSku && $productSku->product_id != $product->id) {
                throw new \Exception(trans('validation.unique', ['attribute' => 'SKU']));
            }
        }

        try {
            DB::beginTransaction();
            hook_action('admin.service.product.create_or_update.before', ['product' => $product, 'data' => $data]);

            $data = hook_filter('admin.service.product.create_or_update.before', ['product' => $product, 'data' => $data]);
            $data = $data['data'];

            $data['brand_id']  = (int) ($data['brand_id'] ?? 0);
            $data['position']  = (int) ($data['position'] ?? 0);
            $data['weight']    = (float) ($data['weight'] ?? 0);
            $data['variables'] = json_decode($data['variables'] ?? '[]');
            $data['shipping']  = (bool) ($data['shipping'] ?? 1);
            $data['video']     = $data['video'] ?? '';
            $product->fill($data);
            $product->updated_at = now();
            $product->save();

            if ($isUpdating) {
                $product->skus()->delete();
                $product->descriptions()->delete();
                $product->attributes()->delete();
            } else {
                $data['images']  = static::moveImagesAndGetNewPaths('product-' . $product->id, $data['images'] ?? []);
                $product->images = $data['images'];
                $product->save();
            }

            $descriptions = [];
            foreach ($data['descriptions'] as $locale => $description) {
                $description['locale']  = $locale;
                $description['content'] = $description['content'] ?? '';

                $descriptions[] = $description;
            }
            $product->descriptions()->createMany($descriptions);

            $product->attributes()->createMany($data['attributes'] ?? []);

            $skus = [];
            foreach ($data['skus'] as $index => $sku) {
                $sku['position']     = $index;
                $sku['origin_price'] = (float) $sku['origin_price'];
                $sku['cost_price']   = (float) $sku['cost_price'];
                $sku['quantity']     = (int) $sku['quantity'];
                $skus[]              = $sku;
            }
            $product->skus()->createMany($skus);

            $product->categories()->sync($data['categories'] ?? []);
            $product->relations()->sync($data['relations'] ?? []);

            $product->refresh();
            hook_action('admin.service.product.create_or_update.after', ['product' => $product, 'data' => $data]);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * $images图片移动到子目录‘product-{$product->id}’并返回新的图片路径
     * @param mixed $dirName
     * @param mixed $images
     */
    public static function moveImagesAndGetNewPaths($dirName, $images)
    {
        // 定义原始路径前缀和目标子目录
        $oldPrefix = 'image/catalog/products/';
        $newSubDir = 'image/catalog/products/' . $dirName . '/';

        // 获取存储路径
        $publicPath = public_path();

        // 遍历图片数组，移动符合条件的图片并更新路径
        foreach ($images as &$image) {
            // 条件为$image在$oldPrefix目录下，但不是在其子目录下
            if (str_starts_with($image, $oldPrefix)) {
                // 获取$oldPrefix之后的部分路径
                $relativePath = substr($image, strlen($oldPrefix));
                // 检查相对路径中是否包含斜杠（如果包含斜杠，表示在子目录中）
                if (strpos($relativePath, '/') === false) {
                    $oldImagePath = $publicPath . '/' . $image;
                    $newImagePath = $publicPath . '/' . $newSubDir . $relativePath;

                    // 确保目标目录存在
                    $newDir = dirname($newImagePath);
                    if (! is_dir($newDir)) {
                        mkdir($newDir, 0755, true);
                    }

                    // 移动图片文件
                    if (file_exists($oldImagePath)) {
                        rename($oldImagePath, $newImagePath);
                    }

                    // 更新图片路径
                    $image = $newSubDir . $relativePath;
                }
            }
        }
        unset($image); // 释放引用

        return $images;
    }
}
