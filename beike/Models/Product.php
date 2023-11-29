<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Base
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['images', 'video', 'position', 'brand_id', 'tax_class_id', 'weight', 'weight_class', 'active', 'shipping', 'variables'];

    protected $casts = [
        'active'    => 'boolean',
        'variables' => 'array',
        'images'    => 'array',
    ];

    protected $appends = ['image'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, ProductCategory::class)->withTimestamps();
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
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

    public function views()
    {
        return $this->hasMany(ProductView::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function masterSku()
    {
        return $this->hasOne(ProductSku::class)->where('is_default', 1);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function relations()
    {
        return $this->belongsToMany(self::class, ProductRelation::class, 'product_id', 'relation_id')->withTimestamps();
    }

    public function inCurrentWishlist()
    {
        $customer   = current_customer();
        $customerId = $customer ? $customer->id : 0;

        return $this->hasOne(CustomerWishlist::class)->where('customer_id', $customerId);
    }

    public function getUrlAttribute()
    {
        $url     = shop_route('products.show', ['product' => $this]);
        $filters = hook_filter('model.product.url', ['url' => $url, 'product' => $this]);

        return $filters['url'] ?? '';
    }

    public function getImageAttribute()
    {
        $images = $this->images ?? [];

        return $images[0] ?? '';
    }
}
