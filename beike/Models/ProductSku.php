<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSku extends Base
{
    use HasFactory;

    protected $fillable = ['product_id', 'variants', 'position', 'images', 'model', 'sku', 'price', 'origin_price', 'cost_price', 'weight', 'quantity', 'is_default'];

    protected $casts = [
        'variants' => 'array',
        'images'   => 'array',
        'is_default'    => 'boolean',
    ];

    protected $appends = ['image'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageAttribute()
    {
        $images = $this->images ?? [];

        return $images[0] ?? '';
    }

    public function getVariantLabel(): string
    {
        $product      = $this->product;
        $localeCode   = locale();
        $variantLabel = '';

        if (empty($product->variables)) {
            return '';
        }

        foreach ($product->variables as $index => $variable) {
            $valueIndex   = $this->variants[$index];
            $variantName  = $variable['name'][$localeCode]                        ?? '';
            $variantValue = $variable['values'][$valueIndex]['name'][$localeCode] ?? '';
            if ($variantName && $variantValue) {
                $variantLabel .= $variantName . ': ' . $variantValue . '; ';
            }
        }

        return $variantLabel;
    }

    public function setWeightAttribute($value)
    {
        $this->attributes['weight'] = is_numeric($value) ? (float) $value : 0;
    }

    public function getWeightAttribute($value)
    {
        return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    }
}
