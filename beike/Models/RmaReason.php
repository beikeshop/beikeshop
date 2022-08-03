<?php
/**
 * RmaReason.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-01 20:22:18
 * @modified   2022-08-01 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RmaReason extends Base
{
    use HasFactory;

    protected $fillable = ['locale','name'];
}

