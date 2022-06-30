<?php
/**
 * Country.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 15:22:18
 * @modified   2022-06-30 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'code', 'sort_order', 'status'];

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }
}

