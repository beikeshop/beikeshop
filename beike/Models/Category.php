<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Base
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function descriptions(): HasMany
    {
        return $this->hasMany(CategoryDescription::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(CategoryDescription::class)->where('locale', locale());
    }

    public function paths(): HasMany
    {
        return $this->hasMany(CategoryPath::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}
