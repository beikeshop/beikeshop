<?php

namespace Plugin\Guangda\Seller\Models;

class Product extends \App\Models\Product
{
    public function getFillable(): array
    {
        $fillable = $this->fillable;
        $fillable[] = 'seller_id';
        return $fillable;
    }
}
