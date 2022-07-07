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
use Illuminate\View\View;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Token;

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
        if ($this->order->status != 'unpaid') {
            throw new \Exception('订单已支付');
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
        $viewPath = "checkout.payment.{$this->paymentMethodCode}";
        if (!view()->exists($viewPath)) {
            throw new \Exception("找不到Payment{$orderPaymentCode}view{$viewPath}");
        }
        $paymentView = view($viewPath, ['order' => $this->order])->render();
        return view('checkout.payment', ['order' => $this->order, 'payment' => $paymentView]);
    }


    /**
     * @throws ApiErrorException
     */
    public function capture($creditCardData): bool
    {
        $apiKey = setting('bk_stripe.secret_key');
        Stripe::setApiKey($apiKey);
        $token = Token::create([
            'card' => [
                'number' => $creditCardData['cardnum'],
                'exp_year' => $creditCardData['year'],
                'exp_month' => $creditCardData['month'],
                'cvc' => $creditCardData['cvv'],
            ],
        ]);

        // $customer = Customer::create([
        //     'email' => $this->order->email,
        // ]);
        // $customerId = $customer['id'];

        $tokenId = $token['id'];
        $total = round($this->order->total, 2) * 100;
        $stripeChargeParameters = array(
            'amount' => $total,
            'currency' => $this->order->currency_code,
            'metadata' => array(
                'orderId' => $this->order->id,
            ),
            'source' => $tokenId,
            // 'customer' => $customerId,
        );

        $charge = \Stripe\Charge::create($stripeChargeParameters);
        return $charge['paid'] && $charge['captured'];
    }
}
