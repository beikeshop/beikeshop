<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerGroup extends Base
{
    use HasFactory;

    protected $fillable = ['total', 'reward_point_factor', 'use_point_factor', 'discount_factor', 'level'];

    public function description()
    {
        return $this->hasOne(CustomerGroupDescription::class)->where('locale', locale());
    }

    public function descriptions()
    {
        return $this->hasMany(CustomerGroupDescription::class);
    }
}
