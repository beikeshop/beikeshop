<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class TaxRate extends Field
{
    protected array $scene = [
        'default' => ['tax_rates.id', 'tax_rates.region_id', 'tax_rates.name', 'tax_rates.rate', 'tax_rates.type', 'tax_rates.created_at'],
    ];
}
