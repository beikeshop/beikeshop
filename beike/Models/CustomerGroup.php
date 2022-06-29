<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $fillable = ['total', 'reward_point_factor', 'use_point_factor', 'discount_factor', 'level'];
}
