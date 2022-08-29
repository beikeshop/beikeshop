<?php
/**
 * OrderHistory.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-08 17:53:53
 * @modified   2022-08-08 17:53:53
 */

namespace Beike\Models;

class OrderHistory extends Base
{
    protected $fillable = [
        'order_id', 'status', 'notify', 'comment'
    ];

    protected $appends = ['status_format'];

    public function getStatusFormatAttribute()
    {
        return trans("order.{$this->status}");
    }
}
