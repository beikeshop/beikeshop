<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class AdminUser extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'name', 'locale', 'password', 'email', 'active',
        ],
    ];
}
