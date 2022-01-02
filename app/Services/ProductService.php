<?php

namespace App\Services;

use App\Models\Product;
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

            $skus = [];
            foreach ($data['skus'] as $index => $rawSku) {
                $skus[] = $rawSku;
            }

            if ($isUpdating) {
                $product->skus()->delete();
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
