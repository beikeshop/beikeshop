<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Base
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['images', 'video', 'position', 'active', 'variables'];
    protected $casts = [
        'active' => 'boolean',
        'variables' => 'array',
        'images' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, ProductCategory::class)->withTimestamps();
    }

    public function description()
    {
        return $this->hasOne(ProductDescription::class)->where('locale', locale());
    }

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function master_sku()
    {
        return $this->hasOne(ProductSku::Class)->where('is_default', 1);
    }

    public function manufacturer()
    {
        return $this->hasOne(Brand::Class, 'id', 'manufacturer_id');
    }

    public function getPriceFormattedAttribute(): string
    {
        return '$' . $this->price;
    }

    public function getUrlAttribute()
    {
        return shop_route('products.show', ['product' => $this]);
    }
}
