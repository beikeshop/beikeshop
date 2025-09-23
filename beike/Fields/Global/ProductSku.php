<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class ProductSku extends Field
{
    protected array $scene = [
        'default' => ['id', 'product_id', 'model', 'quantity',
            'images', 'sku', 'price', 'origin_price', 'cost_price',
            'variants', 'position', 'is_default',
        ],
    ];
}
