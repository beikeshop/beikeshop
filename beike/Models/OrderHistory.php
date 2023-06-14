<?php
/**
 * OrderHistory.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 17:53:53
 * @modified   2022-08-08 17:53:53
 */

namespace Beike\Models;

use Beike\Services\StateMachineService;

class OrderHistory extends Base
{
    protected $fillable = [
        'order_id', 'status', 'notify', 'comment',
    ];

    protected $appends = ['status_format'];

    public function getStatusFormatAttribute()
    {
        $statusMap = array_column(StateMachineService::getAllStatuses(), 'name', 'status');

        return $statusMap[$this->status];
    }
}
