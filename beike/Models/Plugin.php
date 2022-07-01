<?php
/**
 * Plugin.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-01 14:22:15
 * @modified   2022-07-01 14:22:15
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = ['type', 'code'];
}
