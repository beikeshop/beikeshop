<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoryPath extends Base
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'path_id',
        'level',
    ];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function pathCategory(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'path_id');
    }
}
