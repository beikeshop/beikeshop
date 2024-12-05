<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class ProductAttribute extends Field
{
    protected array $scene = [
        'default' => ['id', 'product_id', 'attribute_id', 'attribute_value_id'],
    ];
}
