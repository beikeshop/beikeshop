<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'selected', 'product_id', 'product_sku_id', 'quantity'];

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id', 'id');
    }
}
