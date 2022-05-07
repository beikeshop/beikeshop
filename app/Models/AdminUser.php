<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasFactory;

    const AUTH_GUARD = 'web_admin';

    protected $fillable = ['name', 'email', 'password', 'active'];
}
