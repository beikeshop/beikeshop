<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Customer extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'name', 'email', 'avatar', 'from','password',
            'customer_group_id', 'status', 'created_at', 'active','code','address_id'
        ],
    ];
}
