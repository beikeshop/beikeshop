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

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'name', 'phone', 'country_id', 'state_id', 'state', 'city_id', 'city', 'zipcode', 'address_1', 'address_2'];
}

