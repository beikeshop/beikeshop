<?php

namespace Beike\Models;

use Beike\Notifications\AdminForgottenNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends AuthUser
{
    use HasFactory, HasRoles;
    use Notifiable;

    public const AUTH_GUARD = 'web_admin';

    protected $fillable = ['name', 'email', 'locale', 'password', 'active'];

    public function tokens(): HasMany
    {
        return $this->hasMany(AdminUserToken::class);
    }

    public function notifyVerifyCodeForForgotten($code)
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new AdminForgottenNotification($this, $code));
        } else {
            $this->notifyNow(new AdminForgottenNotification($this, $code));
        }
    }

    public function getIsRootAttribute()
    {
        return $this->id == 1;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
