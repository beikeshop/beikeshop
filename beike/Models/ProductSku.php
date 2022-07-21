<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSku extends Base
{
    use HasFactory;

    protected $fillable = ['product_id', 'variants', 'position', 'image', 'model', 'sku', 'price', 'origin_price', 'cost_price', 'quantity', 'is_default'];

    protected $casts = [
        'variants' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
