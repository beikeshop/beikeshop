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
 */

namespace Plugin\Paypal\Controllers;

use Beike\Repositories\OrderPaymentRepo;
use Beike\Repositories\OrderRepo;
use Beike\Services\StateMachineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plugin\Paypal\Services\PaypalService;
use Srmklive\PayPal\Services\PayPal;

class PaypalController
{
    private PayPal $paypalClient;

    /**
     * Init Paypal
     *
     * @throws \Throwable
     */
    private function initPaypal($order): void
    {
        $paypalService = new PaypalService($order);
        $this->paypalClient = $paypalService->paypalClient;
    }

    /**
     * 创建 paypal 订单
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $data        = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer    = current_customer();
        $order       = OrderRepo::getOrderByNumber($orderNumber, $customer);
        $total       = round($order->total, 2);

        $this->initPaypal($order);
        $paypalOrder = $this->paypalClient->createOrder([
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount'      => [
                        'currency_code' => $order->currency_code,
                        'value'         => $total,
                    ],
                    'description' => 'test',
                ],
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
        $data        = \json_decode($request->getContent(), true);
        $orderNumber = $data['orderNumber'];
        $customer    = current_customer();
        $order       = OrderRepo::getOrderByNumber($orderNumber, $customer);

        $this->initPaypal($order);
        OrderPaymentRepo::createOrUpdatePayment($order->id, ['request' => $data]);
        $paypalOrderId = $data['paypalOrderId'];
        $result        = $this->paypalClient->capturePaymentOrder($paypalOrderId);
        OrderPaymentRepo::createOrUpdatePayment($order->id, ['response' => $result]);

        try {
            DB::beginTransaction();
            if ($result['status'] === 'COMPLETED') {
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
