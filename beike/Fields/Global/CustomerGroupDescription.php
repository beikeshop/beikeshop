<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class CustomerGroupDescription extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'locale', 'customer_group_id', 'description', 'name',
        ],
    ];
}
