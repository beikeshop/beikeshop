<?php
/**
 * Country.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 15:22:18
 * @modified   2022-06-30 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Base
{
    use HasFactory;

    public const CONTINENTS = ['AF', 'AN', 'AS', 'EU', 'NA', 'OA', 'SA', 'null'];

    protected $fillable = ['name', 'country_id', 'code', 'continent', 'sort_order', 'status'];

    protected $appends = ['continent_format'];

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }

    public function getContinentFormatAttribute(): string
    {
        return trans('country.' . $this->continent);
    }
}
