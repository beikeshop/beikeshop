<?php
/**
 * RegionZone.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-27 11:01:01
 * @modified   2022-07-27 11:01:01
 */

namespace Beike\Models;

class RegionZone extends Base
{
    protected $fillable = ['region_id', 'country_id', 'zone_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
