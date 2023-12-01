<?php
/**
 * PaypalService.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-22 11:49:51
 * @modified   2023-08-22 11:49:51
 */

namespace Plugin\Paypal\Services;

use Beike\Shop\Services\PaymentService;
use Srmklive\PayPal\Services\PayPal;

class PaypalService extends PaymentService
{
    public PayPal $paypalClient;

    /**
     * @param             $order
     * @throws \Exception
     * @throws \Throwable
     */
    public function __construct($order)
    {
        parent::__construct($order);
        $this->initPaypal();
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function initPaypal(): void
    {
        $paypalSetting = plugin_setting('paypal');
        $config        = [
            'mode'    => $paypalSetting['sandbox_mode'] ? 'sandbox' : 'live',
            'sandbox' => [
                'client_id'     => $paypalSetting['sandbox_client_id'],
                'client_secret' => $paypalSetting['sandbox_secret'],
            ],
            'live' => [
                'client_id'     => $paypalSetting['live_client_id'],
                'client_secret' => $paypalSetting['live_secret'],
            ],
            'payment_action' => 'Sale',
            'currency'       => system_setting('base.currency'),
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => false,
        ];
        config(['paypal' => null]);
        $this->paypalClient = new PayPal($config);
        $token              = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
    }

    /**
     * 获取支付参数给 uniapp 使用
     * @return array
     * @throws \Throwable
     */
    public function getMobilePaymentData(): array
    {
        $paypalSetting = plugin_setting('paypal');
        $mode          = $paypalSetting['sandbox_mode'] ? 'sandbox' : 'live';

        if ($mode == 'sandbox') {
            $clientId = $paypalSetting['sandbox_client_id'];
        } else {
            $clientId = $paypalSetting['live_client_id'];
        }

        $paypalOrder = $this->createOrder();

        return [
            'clientId'    => $clientId,
            'currency'    => $this->order->currency_code,
            'environment' => $mode,
            'orderId'     => $paypalOrder['id'],
            'userAction'  => 'paynow',  //'paynow/continue'
        ];
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function createOrder(): mixed
    {
        $this->initPaypal();
        $order = $this->order;
        $total = round($order->total, 2);

        return $this->paypalClient->createOrder([
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $order->currency_code,
                        'value'         => $total,
                    ],
                    'description' => 'test',
                ],
            ],
        ]);
    }
}
