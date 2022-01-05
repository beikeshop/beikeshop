<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductDescription;
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

        try {
            DB::beginTransaction();

            $product->fill($data);
            $product->saveOrFail();

            if ($isUpdating) {
                $product->skus()->delete();
                $product->description()->delete();
            }

            $descriptions = [];
            foreach ($data['descriptions'] as $locale => $description) {
                $description['locale'] = $locale;
                $description['content'] = $description['content'] ?? '';

                $descriptions[] = $description;
            }
            $product->descriptions()->createMany($descriptions);

            $skus = [];
            foreach ($data['skus'] as $index => $sku) {
                $sku['position'] = $index;
                $skus[] = $sku;
            }
            $product->skus()->createMany($skus);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
