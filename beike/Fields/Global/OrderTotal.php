<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class OrderTotal extends Field
{
    protected array $scene = [
        'default' => ['order_id', 'code', 'value', 'title'],
    ];
}
