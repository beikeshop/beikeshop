<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Category extends Field
{
    protected array $scene = [
        'default' => [
            'categories.id', 'categories.parent_id', 'categories.image', 'categories.position', 'categories.active', 'categories.created_at',
        ],
    ];
}
