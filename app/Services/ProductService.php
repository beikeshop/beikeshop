<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $product = new Product($data);
            if (isset($data['variant'])) {
                $product->variable = json_encode($data['variant']);
            }
            $product->saveOrFail();

            $skus = [];
            foreach ($data['skus'] as $index => $rawSku) {
                $sku = $rawSku;
                $sku['is_default'] = $index == 0;
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
