<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Brand extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'name', 'logo', 'sort_order', 'first', 'status',
        ],
    ];
}
