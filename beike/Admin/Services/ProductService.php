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

            $data['brand_id']  = (int) ($data['brand_id'] ?? 0);
            $data['position']  = (int) ($data['position'] ?? 0);
            $data['weight']    = (float) ($data['weight'] ?? 0);
            $data['variables'] = json_decode($data['variables'] ?? '[]');
            $data['shipping']  = (bool) ($data['shipping'] ?? 1);
            $product->fill($data);
            $product->updated_at = now();
            $product->save();

            if ($isUpdating) {
                $product->skus()->delete();
                $product->descriptions()->delete();
                $product->attributes()->delete();
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

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
