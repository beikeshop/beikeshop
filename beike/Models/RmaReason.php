<?php
/**
 * RmaReason.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-01 20:22:18
 * @modified   2022-08-01 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RmaReason extends Base
{
    use HasFactory;

    protected $fillable = ['locale', 'name'];
}
