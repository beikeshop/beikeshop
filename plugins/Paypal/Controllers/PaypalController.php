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

use Beike\Repositories\OrderRepo;
use Beike\Services\StateMachineService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal;

class PaypalController
{
    private PayPal $paypalClient;

    /**
     * PaypalController constructor.
     * @throws \Throwable
     */
    public function __construct()
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
            'validate_ssl' => true,
        ];
        config(['paypal' => null]);
        $this->paypalClient = new PayPal($config);
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
    }

    public function test()
    {

    }


    /**
     * 创建 paypal 订单
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($orderNumber, $customer);
        $orderTotalUsd = currency_format($order->total, 'USD', '', false);

        $paypalOrder = $this->paypalClient->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => 'USD',
                        "value" => round($orderTotalUsd, 2),
                    ],
                    'description' => 'test'
                ]
            ],
        ]);

        return response()->json($paypalOrder);
        //return redirect($paypalOrder['links'][1]['href'])->send();
    }


    /**
     * 客户同意后扣款回调
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function capture(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($orderNumber, $customer);

        $paypalOrderId = $data['paypalOrderId'];
        $result = $this->paypalClient->capturePaymentOrder($paypalOrderId);

        try {
            DB::beginTransaction();
            if ($result['status'] === "COMPLETED") {
                StateMachineService::getInstance($order)->changeStatus('paid');
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return response()->json($result);
    }
}
