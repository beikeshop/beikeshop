<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class OrderPayment extends Field
{
    protected array $scene = [
        'default' => ['id', 'order_id', 'transaction_id', 'request', 'response', 'callback', 'receipt', 'created_at'],
    ];
}
