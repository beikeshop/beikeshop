<?php
/**
 * Address.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-28 15:22:18
 * @modified   2022-06-28 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'name', 'phone', 'country_id', 'zone_id', 'zone', 'city_id', 'city', 'zipcode', 'address_1', 'address_2'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}

