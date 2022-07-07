<?php
/**
 * PaymentService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-06 17:33:06
 * @modified   2022-07-06 17:33:06
 */

namespace Beike\Shop\Services;

use Beike\Models\Order;
use Beike\Repositories\OrderRepo;
use Cartalyst\Stripe\Stripe;

class PaymentService
{
    private $order;

    private $orderId;

    private $paymentMethodCode;

    public function __construct($order)
    {
        $customer = current_customer();
        if (is_numeric($order)) {
            $this->order = OrderRepo::getOrderByIdOrNumber($this->orderId, $customer);
        } elseif ($order instanceof Order) {
            $this->order = $order;
        }
        if (empty($this->order)) {
            throw new \Exception("无效订单");
        }
        $this->orderId = (int)$this->order->id;
        $this->paymentMethodCode = $this->order->payment_method_code;
    }


    public function pay()
    {
        if ($this->paymentMethodCode == 'bk_stripe') {
            $apiKey = setting('bk_stripe.secret_key');
            $stripe = Stripe::make($apiKey, '2020-08-27');

            /**
            $customer = $stripe->customers()->create([
                'email' => $this->order->email,
            ]);

            $customers = $stripe->customers()->all();

            $charge = $stripe->charges()->create([
                'customer' => $customer['id'],
                'currency' => 'USD',
                'amount'   => 50.49,
            ]);
             **/

            return view("checkout.payment.{$this->paymentMethodCode}");
            // echo $charge['id'];
        }
    }
}
