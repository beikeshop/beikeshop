<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Address extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'customer_id', 'name', 'phone', 'country_id', 'zone_id', 'zone',
            'city_id', 'city', 'zipcode', 'address_1', 'address_2', 'created_at',
        ],
    ];
}
