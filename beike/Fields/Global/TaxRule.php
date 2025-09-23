<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class TaxRule extends Field
{
    protected array $scene = [
        'default' => ['id', 'tax_class_id', 'tax_rate_id', 'based', 'priority', 'created_at', 'updated_at'],
    ];
}
