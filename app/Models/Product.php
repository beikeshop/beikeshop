<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'video', 'position', 'active', 'variables'];

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function getVariablesDecodedAttribute()
    {
        return json_decode($this->variables, true);
    }
}
