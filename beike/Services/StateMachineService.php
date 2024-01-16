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
use Beike\Repositories\OrderPaymentRepo;
use Throwable;

class StateMachineService
{
    private Order $order;

    private int $orderId;

    private string $comment;

    private bool $notify;

    private array $shipment;

    private array $payment;

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
     * 设置支付信息
     *
     * @param array $payment
     * @return $this
     */
    public function setPayment(array $payment = []): self
    {
        $this->payment = $payment;

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

        return hook_filter('service.state_machine.all_statuses', $result);
    }

    /**
     * 获取所有有效订单状态(从订单已支付到订单已完成的所有状态)
     * @return string[]
     */
    public static function getValidStatuses(): array
    {
        $statuses = [
            self::PAID,
            self::SHIPPED,
            self::COMPLETED,
        ];

        return $statuses;
    }

    /**
     * 获取当前订单可以变为的状态
     *
     * @return array
     * @throws \Exception
     */
    public function nextBackendStatuses(): array
    {
        $machines = $this->getMachines();

        $currentStatusCode = $this->order->status;
        $nextStatus        = $machines[$currentStatusCode] ?? [];

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
     * @param             $status
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
        if ($functions) {
            foreach ($functions as $function) {
                if ($function instanceof \Closure) {
                    $function();

                    continue;
                }

                if (! method_exists($this, $function)) {
                    throw new \Exception("{$function} not exist in StateMachine!");
                }
                $this->{$function}($oldStatusCode, $status);
            }
        }

        hook_filter('service.state_machine.change_status.after', ['order' => $order, 'status' => $status, 'comment' => $comment, 'notify' => $notify]);

        if (! $order->shipping_method_code && $status == self::PAID) {
            $this->changeStatus(self::COMPLETED, $comment, $notify);
        }
    }

    /**
     * 检测当前订单是否可以变更为某个状态
     *
     * @param             $statusCode
     * @throws \Exception
     */
    private function validStatusCode($statusCode)
    {
        $orderId           = $this->orderId;
        $orderNumber       = $this->order->number;
        $currentStatusCode = $this->order->status;

        $nextStatusCodes = collect($this->nextBackendStatuses())->pluck('status')->toArray();
        if (! in_array($statusCode, $nextStatusCodes)) {
            throw new \Exception("Order {$orderId}({$orderNumber}) is {$currentStatusCode}, cannot be changed to $statusCode");
        }
    }

    /**
     * 获取状态机流程, 此处通过 filter hook 可以被外部插件修改
     * @return mixed
     */
    private function getMachines()
    {
        $data = [
            'order'    => $this->order,
            'machines' => self::MACHINES,
        ];

        $data = hook_filter('service.state_machine.machines', $data);

        return $data['machines'] ?? [];
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
        $machines = $this->getMachines();

        return $machines[$oldStatus][$newStatus] ?? [];
    }

    /**
     * 更新订单状态
     *
     * @param             $oldCode
     * @param             $newCode
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
            'orderProducts',
        ]);
        $orderProducts = $this->order->orderProducts;
        foreach ($orderProducts as $orderProduct) {
            Product::query()->where('id', $orderProduct->product_id)->increment('sales', $orderProduct->quantity);
        }
    }

    /**
     * 添加更改记录
     *
     * @param            $oldCode
     * @param            $newCode
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
     * 添加发货单号
     * @throws Throwable
     */
    private function addPayment($oldCode, $newCode)
    {
        if (empty($this->payment)) {
            return;
        }
        OrderPaymentRepo::createOrUpdatePayment($this->orderId, $this->payment);
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
