<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Currency extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'name', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'status', 'created_at',
        ],
    ];
}
