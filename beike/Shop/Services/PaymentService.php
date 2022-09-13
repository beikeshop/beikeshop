<?php
/**
 * PaymentService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-06 17:33:06
 * @modified   2022-07-06 17:33:06
 */

namespace Beike\Shop\Services;

use Beike\Models\Order;
use Beike\Repositories\OrderRepo;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Token;

class PaymentService
{
    protected $order;
    protected $orderId;
    protected $paymentMethodCode;

    public function __construct($order)
    {
        $customer = current_customer();
        if (is_numeric($order)) {
            $this->order = OrderRepo::getOrderByIdOrNumber($order, $customer);
        } elseif ($order instanceof Order) {
            $this->order = $order;
        }
        if (empty($this->order)) {
            throw new \Exception(trans('shop/order.invalid_order'));
        }
        if ($this->order->status != 'unpaid') {
            throw new \Exception(trans('shop/order.order_already_paid'));
        }
        $this->orderId = (int)$this->order->id;
        $this->paymentMethodCode = $this->order->payment_method_code;
    }


    /**
     * @throws \Exception
     */
    public function pay()
    {
        $orderPaymentCode = $this->paymentMethodCode;
        $paymentCode = Str::studly($orderPaymentCode);
        $viewPath = "$paymentCode::checkout.payment";
        if (!view()->exists($viewPath)) {
            throw new \Exception("找不到支付方式 {$orderPaymentCode} 模板 {$viewPath}");
        }
        $paymentData = [
            'order' => $this->order,
            'payment_setting' => plugin_setting($orderPaymentCode),
        ];
        $paymentView = view($viewPath, $paymentData)->render();
        return view('checkout.payment', ['order' => $this->order, 'payment' => $paymentView]);
    }
}
