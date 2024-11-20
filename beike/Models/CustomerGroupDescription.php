<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerGroupDescription extends Base
{
    use HasFactory;

    protected $fillable = ['customer_group_id', 'locale', 'name', 'description'];
}
