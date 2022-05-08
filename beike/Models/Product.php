<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['image', 'video', 'position', 'active', 'variables'];
    protected $attributes = [
        'image' => ''
    ];
    protected $casts = [
        'active' => 'boolean',
    ];

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

    public function getPriceFormattedAttribute(): string
    {
        return '$' . $this->price;
    }

    public function getVariablesDecodedAttribute()
    {
        return json_decode($this->variables, true);
    }
}
