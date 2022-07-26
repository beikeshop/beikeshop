<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSku extends Base
{
    use HasFactory;

    protected $fillable = ['product_id', 'variants', 'position', 'images', 'model', 'sku', 'price', 'origin_price', 'cost_price', 'quantity', 'is_default'];

    protected $casts = [
        'variants' => 'array',
        'images' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
