<?php
/**
 * Brand.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-27 20:22:18
 * @modified   2022-07-27 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Base
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'code', 'sort_order', 'status'];

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }

    public function products() :HasMany
    {
        return $this->hasMany(Product::Class);
    }
}

