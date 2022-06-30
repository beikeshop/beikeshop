<?php
/**
 * Zone.php
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

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'sort_order', 'status'];
}

