<?php
/**
 * RmaHistory.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-03 15:22:18
 * @modified   2022-08-03 15:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RmaHistory extends Base
{
    use HasFactory;

    protected $fillable = ['rma_id', 'status', 'notify', 'comment'];
}

