<?php

namespace Beike\Models;

use Beike\Notifications\AdminForgottenNotification;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUser extends AuthUser
{
    use HasFactory, HasRoles;
    use Notifiable;

    const AUTH_GUARD = 'web_admin';

    protected $fillable = ['name', 'email', 'locale', 'password', 'active'];


    public function notifyVerifyCodeForForgotten($code)
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new AdminForgottenNotification($this, $code));
        } else {
            $this->notifyNow(new AdminForgottenNotification($this, $code));
        }
    }
}
