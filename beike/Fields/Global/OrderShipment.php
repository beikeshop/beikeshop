<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class OrderShipment extends Field
{
    protected array $scene = [
        'default' => ['id', 'order_id', 'express_code', 'express_company', 'express_number', 'created_at'],
    ];
}
