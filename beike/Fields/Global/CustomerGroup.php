<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class CustomerGroup extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'total', 'reward_point_factor', 'use_point_factor', 'discount_factor', 'level',
        ],
    ];
}
