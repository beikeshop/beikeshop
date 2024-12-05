<?php

namespace Beike\Fields\Global;

use Beike\Fields\Field;

class PageDescription extends Field
{
    protected array $scene = [
        'default' => ['id', 'page_id', 'locale', 'title', 'summary', 'content', 'meta_title', 'meta_description',
            'meta_keywords', 'created_at', 'updated_at',
        ],
    ];
}
