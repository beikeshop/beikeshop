<?php

namespace Beike\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUser extends AuthUser
{
    use HasFactory, HasRoles;

    const AUTH_GUARD = 'web_admin';

    protected $fillable = ['name', 'email', 'locale', 'password', 'active'];
}
