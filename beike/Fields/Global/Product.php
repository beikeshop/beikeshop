<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Product extends Field
{
    protected array $scene = [
        'default' => [
            'products.id',
            'products.brand_id',
            'products.images',
            'products.price',
            'products.video',
            'products.position',
            'products.shipping',
            'products.active',
            'products.variables',
            'products.tax_class_id',
            'products.weight',
            'products.weight_class',
            'products.sales',
            'products.created_at',
            'products.deleted_at',
        ],
    ];
}
