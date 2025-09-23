<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class CategoryDescription extends Field
{
    protected array $scene = [
        'default' => [
            'id', 'category_id', 'locale', 'name', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'created_at',
        ],
    ];
}
