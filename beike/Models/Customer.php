<?php

namespace Beike\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    const AUTH_GUARD = 'web_shop';

    protected $fillable = ['name', 'email', 'password', 'status', 'avatar', 'customer_group_id', 'locale', 'status', 'from'];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function wishlists() : HasMany
    {
        return $this->hasMany(CustomerWishlist::class);
    }

    public function rmas() : HasMany
    {
        return $this->hasMany(Rma::class);
    }
}
