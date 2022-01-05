<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
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
