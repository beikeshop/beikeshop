<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerWishlist extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id'];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
