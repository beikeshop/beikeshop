<?php
/**
 * Base.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-21 18:27:45
 * @modified   2022-07-21 18:27:45
 */

namespace Beike\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }
}
