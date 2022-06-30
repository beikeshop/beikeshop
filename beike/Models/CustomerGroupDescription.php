<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroupDescription extends Model
{
    use HasFactory;

    protected $fillable = ['locale', 'name', 'description'];
}
