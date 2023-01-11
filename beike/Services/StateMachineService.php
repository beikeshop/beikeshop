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

use Beike\Models\Order;
use Beike\Models\OrderHistory;
use Beike\Models\OrderShipment;
use Beike\Models\Product;
use Throwable;

class StateMachineService
{
    private Order $order;

    private int $orderId;

    private string $comment;

    private bool $notify;

    private array $shipment;

    public const CREATED = 'created';                  // 已创建

    public const UNPAID = 'unpaid';                    // 待支付

    public const PAID = 'paid';                        // 已支付

    public const SHIPPED = 'shipped';                  // 已发货

    public const COMPLETED = 'completed';              // 已完成

    public const CANCELLED = 'cancelled';              // 已取消

    public const ORDER_STATUS = [
        self::CREATED,
        self::UNPAID,
        self::PAID,
        self::SHIPPED,
        self::COMPLETED,
        self::CANCELLED,
    ];

    public const MACHINES = [
        self::CREATED => [
            self::UNPAID => ['updateStatus', 'addHistory', 'notifyNewOrder'],
        ],
        self::UNPAID  => [
            self::PAID      => ['updateStatus', 'addHistory', 'updateSales', 'subStock', 'notifyUpdateOrder'],
            self::CANCELLED => ['updateStatus', 'addHistory', 'notifyUpdateOrder'],
        ],
        self::PAID    => [
            self::CANCELLED => ['updateStatus', 'addHistory', 'notifyUpdateOrder'],
            self::SHIPPED   => ['updateStatus', 'addHistory', 'addShipment', 'notifyUpdateOrder'],
            self::COMPLETED => ['updateStatus', 'addHistory', 'notifyUpdateOrder'],
        ],
        self::SHIPPED => [
            self::COMPLETED => ['updateStatus', 'addHistory', 'notifyUpdateOrder'],
        ],
    ];

    public function __construct(Order $order)
    {
        $this->order   = $order;
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
        $this->notify = (bool) $flag;

        return $this;
    }

    /**
     * 设置发货信息
     *
     * @param array $shipment
     * @return $this
     */
    public function setShipment(array $shipment = []): self
    {
        $this->shipment = $shipment;

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
        $result   = [];
        $statuses = self::ORDER_STATUS;
        foreach ($statuses as $status) {
            if ($status == self::CREATED) {
                continue;
            }
            $result[] = [
                'status' => $status,
                'name'   => trans("order.{$status}"),
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
        $nextStatus        = self::MACHINES[$currentStatusCode] ?? [];

        if (empty($nextStatus)) {
            return [];
        }
        $nextStatusCodes = array_keys($nextStatus);
        $result          = [];
        foreach ($nextStatusCodes as $status) {
            $result[] = [
                'status' => $status,
                'name'   => trans("order.{$status}"),
            ];
        }

        return $result;
    }

    /**
     * @param $status
     * @param  string     $comment
     * @param  bool       $notify
     * @throws \Exception
     */
    public function changeStatus($status, string $comment = '', bool $notify = false)
    {
        $order         = $this->order;
        $oldStatusCode = $order->status;
        $newStatusCode = $status;

        $this->setComment($comment)->setNotify($notify);

        $this->validStatusCode($status);
        $functions = $this->getFunctions($oldStatusCode, $newStatusCode);
        if (empty($functions)) {
            return;
        }
        foreach ($functions as $function) {
            if (! method_exists($this, $function)) {
                throw new \Exception("{$function} not exist in StateMachine!");
            }
            $this->{$function}($oldStatusCode, $status);
        }
    }

    /**
     * 检测当前订单是否可以变更为某个状态
     *
     * @param $statusCode
     * @throws \Exception
     */
    private function validStatusCode($statusCode)
    {
        if (! in_array($statusCode, self::ORDER_STATUS)) {
            $statusCodeString = implode(', ', self::ORDER_STATUS);

            throw new \Exception("Invalid order status, must be one of the '{$statusCodeString}'");
        }
        $orderId           = $this->orderId;
        $orderNumber       = $this->order->number;
        $currentStatusCode = $this->order->status;

        $nextStatusCodes = collect($this->nextBackendStatuses())->pluck('status')->toArray();
        if (! in_array($statusCode, $nextStatusCodes)) {
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
     * 更新订单商品销量
     * @return void
     */
    private function updateSales()
    {
        $this->order->loadMissing([
            'orderProducts'
        ]);
        $orderProducts = $this->order->orderProducts;
        foreach ($orderProducts as $orderProduct) {
            Product::query()->where('id', $orderProduct->product_id)->increment('sales', $orderProduct->quantity);
        }
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
            'status'   => $newCode,
            'notify'   => (int) $this->notify,
            'comment'  => (string) $this->comment,
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
            'orderProducts.productSku',
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
     * 添加发货单号
     */
    private function addShipment($oldCode, $newCode)
    {
        $shipment       = $this->shipment;
        $expressCode    = $shipment['express_code']    ?? '';
        $expressCompany = $shipment['express_company'] ?? '';
        $expressNumber  = $shipment['express_number']  ?? '';
        if ($expressCode && $expressCompany && $expressNumber) {
            $orderShipment = new OrderShipment([
                'order_id'        => $this->orderId,
                'express_code'    => $expressCode,
                'express_company' => $expressCompany,
                'express_number'  => $expressNumber,
            ]);
            $orderShipment->saveOrFail();
        }
    }

    /**
     * 发送新订单通知
     */
    private function notifyNewOrder($oldCode, $newCode)
    {
        if (! $this->notify) {
            return;
        }
        $this->order->notifyNewOrder();
    }

    /**
     * 发送订单状态更新通知
     */
    private function notifyUpdateOrder($oldCode, $newCode)
    {
        if (! $this->notify) {
            return;
        }
        $this->order->notifyUpdateOrder($oldCode);
    }

    /**
     * 恢复库存
     */
    private function revertStock($oldCode, $newCode)
    {

    }
}
