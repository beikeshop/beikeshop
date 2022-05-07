<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'path_id',
        'level',
    ];
}
