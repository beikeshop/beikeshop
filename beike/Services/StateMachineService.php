<?php
/**
 * StateMachineService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-08 18:21:47
 * @modified   2022-08-08 18:21:47
 */

namespace Beike\Services;

use Beike\Models\Order;
use Beike\Models\OrderHistory;

class StateMachineService
{
    private Order $order;
    private int $orderId;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->orderId = $order->id;
    }


    /**
     * @param $status
     * @param string $comment
     * @param false $notify
     * @throws \Throwable
     */
    public function changeStatus($status, string $comment = '', bool $notify = false)
    {
        $order = $this->order;

        $this->createOrderHistory($status, $comment, $notify);
        $order->status = $status;
        $order->saveOrFail();
    }


    /**
     * @param $status
     * @param string $comment
     * @param false $notify
     * @throws \Throwable
     */
    private function createOrderHistory($status, string $comment = '', bool $notify = false)
    {
        $history = new OrderHistory([
            'order_id' => $this->orderId,
            'status' => $status,
            'comment' => $comment,
            'notify' => $notify
        ]);
        $history->saveOrFail();
    }
}
