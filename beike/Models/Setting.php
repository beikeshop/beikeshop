<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Base
{
    use HasFactory;

    const TYPES = ['system', 'plugin'];

    protected $table = 'settings';
    protected $fillable = ['type', 'space', 'name', 'value', 'json'];
}
