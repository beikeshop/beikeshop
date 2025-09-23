<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class OrderHistory extends Field
{
    protected array $scene = [
        'default' => ['order_id', 'status', 'comment', 'comment', 'created_at'],
    ];
}
