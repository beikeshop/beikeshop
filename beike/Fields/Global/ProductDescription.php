<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class ProductDescription extends Field
{
    protected array $scene = [
        'default' => ['product_id', 'name', 'content', 'meta_title', 'meta_keywords', 'meta_description'],
    ];
}
