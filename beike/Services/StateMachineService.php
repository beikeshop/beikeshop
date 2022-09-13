<?php
/**
 * StateMachineService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 18:21:47
 * @modified   2022-08-08 18:21:47
 */

namespace Beike\Services;

use Throwable;
use Beike\Models\Order;
use Beike\Models\OrderHistory;

class StateMachineService
{
    private Order $order;
    private int $orderId;
    private string $comment;
    private bool $notify;

    const CREATED = 'created';                  // 已创建
    const UNPAID = 'unpaid';                    // 待支付
    const PAID = 'paid';                        // 已支付
    const SHIPPED = 'shipped';                  // 已发货
    const COMPLETED = 'completed';              // 已完成
    const CANCELLED = 'cancelled';              // 已取消

    const ORDER_STATUS = [
        self::CREATED,
        self::UNPAID,
        self::PAID,
        self::SHIPPED,
        self::COMPLETED,
        self::CANCELLED,
    ];

    const MACHINES = [
        self::CREATED => [
            self::UNPAID => ['updateStatus', 'addHistory'],
        ],
        self::UNPAID => [
            self::PAID => ['updateStatus', 'addHistory', 'subStock'],
            self::CANCELLED => ['updateStatus', 'addHistory'],
        ],
        self::PAID => [
            self::CANCELLED => ['updateStatus', 'addHistory'],
            self::SHIPPED => ['updateStatus', 'addHistory'],
            self::COMPLETED => ['updateStatus', 'addHistory']
        ],
        self::SHIPPED => [
            self::COMPLETED => ['updateStatus', 'addHistory',]
        ]
    ];

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->orderId = $order->id;
    }

    public static function getInstance($order): self
    {
        return new self($order);
    }

    /**
     * 设置订单备注
     * @param $comment
     * @return $this
     */
    public function setComment($comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * 设置是否通知
     * @param $flag
     * @return $this
     */
    public function setNotify($flag): self
    {
        $this->notify = (bool)$flag;
        return $this;
    }


    /**
     * 获取所有订单状态列表
     *
     * @return array
     * @throws \Exception
     */
    public static function getAllStatuses(): array
    {
        $result = [];
        $statuses = self::ORDER_STATUS;
        foreach ($statuses as $status) {
            if ($status == self::CREATED) {
                continue;
            }
            $result[] = [
                'status' => $status,
                'name' => trans($status)
            ];
        }
        return $result;
    }

    /**
     * 获取当前订单可以变为的状态
     *
     * @return array
     * @throws \Exception
     */
    public function nextBackendStatuses(): array
    {
        $currentStatusCode = $this->order->status;
        $nextStatus = self::MACHINES[$currentStatusCode] ?? [];

        if (empty($nextStatus)) {
            return [];
        }
        $nextStatusCodes = array_keys($nextStatus);
        $result = [];
        foreach ($nextStatusCodes as $status) {
            $result[] = [
                'status' => $status,
                'name' => trans("order.{$status}")
            ];
        }
        return $result;
    }

    /**
     * @param $status
     * @param string $comment
     * @param bool $notify
     * @throws \Exception
     */
    public function changeStatus($status, string $comment = '', bool $notify = false)
    {
        $order = $this->order;
        $oldStatusCode = $order->status;
        $newStatusCode = $status;

        $this->setComment($comment)->setNotify($notify);

        $this->validStatusCode($status);
        $functions = $this->getFunctions($oldStatusCode, $newStatusCode);
        if (empty($functions)) {
            return;
        }
        foreach ($functions as $function) {
            if (!method_exists($this, $function)) {
                throw new \Exception("{$function} not exist in StateMachine!");
            }
            $this->{$function}($oldStatusCode, $status);
        }
    }

    /**
     *
     * 检测当前订单是否可以变更为某个状态
     *
     * @param $statusCode
     * @throws \Exception
     */
    private function validStatusCode($statusCode)
    {
        if (!in_array($statusCode, self::ORDER_STATUS)) {
            $statusCodeString = implode(', ', self::ORDER_STATUS);
            throw new \Exception("Invalid order status, must be one of the '{$statusCodeString}'");
        }
        $orderId = $this->orderId;
        $orderNumber = $this->order->number;
        $currentStatusCode = $this->order->status;

        $nextStatusCodes = collect($this->nextBackendStatuses())->pluck('status')->toArray();
        if (!in_array($statusCode, $nextStatusCodes)) {
            throw new \Exception("Order {$orderId}({$orderNumber}) is {$currentStatusCode}, cannot be changed to $statusCode");
        }
    }

    /**
     * 通过订单当前状态以及即将变为的状态获取需要触发的事件
     *
     * @param $oldStatus
     * @param $newStatus
     * @return array
     */
    private function getFunctions($oldStatus, $newStatus): array
    {
        return self::MACHINES[$oldStatus][$newStatus] ?? [];
    }


    /**
     * 更新订单状态
     *
     * @param $oldCode
     * @param $newCode
     * @throws \Throwable
     */
    private function updateStatus($oldCode, $newCode)
    {
        $this->order->status = $newCode;
        $this->order->saveOrFail();
    }

    /**
     * 添加更改记录
     *
     * @param $oldCode
     * @param $newCode
     * @throws Throwable
     */
    private function addHistory($oldCode, $newCode)
    {
        $history = new OrderHistory([
            'order_id' => $this->orderId,
            'status' => $newCode,
            'notify' => (int)$this->notify,
            'comment' => (string)$this->comment,
        ]);
        $history->saveOrFail();
    }


    /**
     * 减扣库存
     *
     * @param $oldCode
     * @param $newCode
     */
    private function subStock($oldCode, $newCode)
    {
        $this->order->loadMissing([
            'orderProducts.productSku'
        ]);
        $orderProducts = $this->order->orderProducts;
        foreach ($orderProducts as $orderProduct) {
            $productSku = $orderProduct->productSku;
            if (empty($productSku)) {
                continue;
            }
            $productSku->decrement('quantity', $orderProduct->quantity);
        }
    }


    /**
     * 恢复库存
     */
    private function revertStock($oldCode, $newCode)
    {

    }
}
