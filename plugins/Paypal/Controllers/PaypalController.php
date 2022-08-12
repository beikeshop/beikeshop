<?php
/**
 * PaypalController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
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
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency' => 'USD',
            'notify_url' => '', // Change this accordingly for your application.
            'locale' => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl' => true, // Validate SSL when creating api client.
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
        $orderNumber = $data['order_number'];
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
    public function capture(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $orderId = $data['orderId'];
        $this->paypalClient->setApiCredentials(config('paypal'));
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
        $result = $this->paypalClient->capturePaymentOrder($orderId);

//            $result = $result->purchase_units[0]->payments->captures[0];
        try {
            DB::beginTransaction();
            if ($result['status'] === "COMPLETED") {
                $transaction = new Transaction;
                $transaction->vendor_payment_id = $orderId;
                $transaction->payment_gateway_id = $data['payment_gateway_id'];
                $transaction->user_id = $data['user_id'];
                $transaction->status = TransactionStatus::COMPLETED;
                $transaction->save();
                $order = Order::where('vendor_order_id', $orderId)->first();
                $order->transaction_id = $transaction->id;
                $order->status = TransactionStatus::COMPLETED;
                $order->save();
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return response()->json($result);
    }
}
