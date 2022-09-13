<?php
/**
 * Currency.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 15:22:18
 * @modified   2022-06-30 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Base
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'status'];

}

