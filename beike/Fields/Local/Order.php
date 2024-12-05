<?php

namespace Beike\Fields\Local;

use Beike\Fields\Field;

class Order extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'number', 'customer_name', 'payment_method_name', 'status', 'total', 'currency_code',
            'currency_value', 'created_at', 'updated_at', 'deleted_at', 'shipping_method_name', 'email', 'shipping_country',
            'shipping_customer_name', 'shipping_telephone', 'shipping_address_1', 'shipping_address_2',
            'shipping_city', 'shipping_zone', 'shipping_zone_id', 'shipping_zipcode', 'payment_customer_name', 'payment_telephone',
            'payment_address_1', 'payment_address_2', 'payment_city', 'payment_zone', 'payment_zone_id', 'payment_country', 'payment_zipcode',
            'comment', 'customer_id',
        ],
    ];
}
