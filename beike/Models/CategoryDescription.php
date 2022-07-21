<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryDescription extends Base
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'locale',
        'name',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
