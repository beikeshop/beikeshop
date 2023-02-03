<?php
/**
 * PaypalController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-10 18:57:56
 * @modified   2022-08-10 18:57:56
 *
 * https://www.zongscan.com/demo333/1311.html
 * https://clickysoft.com/how-to-integrate-paypal-payment-gateway-in-laravel/
 * https://www.positronx.io/how-to-integrate-paypal-payment-gateway-in-laravel/
 *
 */

namespace Plugin\Paypal\Controllers;

use Illuminate\Http\Request;
use Beike\Repositories\OrderRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal;
use Beike\Services\StateMachineService;

class PaypalController
{
    private PayPal $paypalClient;

    private function initPaypal()
    {
        $paypalSetting = plugin_setting('paypal');
        $config = [
            'mode' => $paypalSetting['sandbox_mode'] ? 'sandbox' : 'live',
            'sandbox' => [
                'client_id' => $paypalSetting['sandbox_client_id'],
                'client_secret' => $paypalSetting['sandbox_secret'],
            ],
            'live' => [
                'client_id' => $paypalSetting['live_client_id'],
                'client_secret' => $paypalSetting['live_secret'],
            ],
            'payment_action' => 'Sale',
            'currency' => 'USD',
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => false,
        ];
        config(['paypal' => null]);
        $this->paypalClient = new PayPal($config);
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
    }


    /**
     * 创建 paypal 订单
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $this->initPaypal();
        $data = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($orderNumber, $customer);
        $total = round($order->total, 2);

        $paypalOrder = $this->paypalClient->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $order->currency_code,
                        "value" => $total,
                    ],
                    'description' => 'test'
                ]
            ],
        ]);

        return response()->json($paypalOrder);
    }


    /**
     * 客户同意后扣款回调
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function capture(Request $request): JsonResponse
    {
        $this->initPaypal();
        $data = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($orderNumber, $customer);

        $paypalOrderId = $data['paypalOrderId'];
        $result = $this->paypalClient->capturePaymentOrder($paypalOrderId);

        try {
            DB::beginTransaction();
            if ($result['status'] === "COMPLETED") {
                StateMachineService::getInstance($order)->changeStatus(StateMachineService::PAID);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return response()->json($result);
    }
}
