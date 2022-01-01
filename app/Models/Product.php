<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'video', 'sort_order', 'status', 'variable'];

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function getVariableDecodedAttribute()
    {
        return json_decode($this->variable, true);
    }
}
