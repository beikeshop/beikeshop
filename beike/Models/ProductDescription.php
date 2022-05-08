<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'name', 'content', 'meta_title', 'meta_description', 'meta_keyword'];
}
