<?php
/**
 * Language.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-05 16:32:18
 * @modified   2022-07-05 16:32:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Base
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'locale', 'image', 'sort_order', 'status'];
}
