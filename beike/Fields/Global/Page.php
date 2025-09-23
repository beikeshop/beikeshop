<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class Page extends Field
{
    protected array $scene = [
        'default' => ['id', 'page_category_id', 'image', 'position', 'views', 'author',
            'active', 'created_at', 'updated_at',
        ],
    ];
}
