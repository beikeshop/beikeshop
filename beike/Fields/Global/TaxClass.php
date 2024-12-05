<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class TaxClass extends Field
{
    protected array $scene = [
        'default' => ['id', 'title', 'description', 'created_at', 'updated_at'],
    ];
}
