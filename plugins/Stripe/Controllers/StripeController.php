<?php
/**
 * StripeController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 15:58:36
 * @modified   2022-08-08 15:58:36
 */

namespace Plugin\Stripe\Controllers;

use Beike\Repositories\OrderPaymentRepo;
use Beike\Repositories\OrderRepo;
use Beike\Services\StateMachineService;
use Beike\Shop\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Plugin\Stripe\Services\StripePaymentService;

class StripeController extends Controller
{
    /**
     * 订单支付扣款
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function capture(Request $request): JsonResponse
    {
        try {
            $number         = request('order_number');
            $customer       = current_customer();
            $order          = OrderRepo::getOrderByNumber($number, $customer);
            $creditCardData = $request->all();

            OrderPaymentRepo::createOrUpdatePayment($order->id, ['request' => $creditCardData]);
            $result = (new StripePaymentService($order))->capture($creditCardData);
            OrderPaymentRepo::createOrUpdatePayment($order->id, ['response' => $result]);

            if ($result) {
                StateMachineService::getInstance($order)->setShipment()->changeStatus(StateMachineService::PAID);

                return json_success(trans('Stripe::common.capture_success'));
            }

            return json_success(trans('Stripe::common.capture_fail'));

        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Webhook from stripe
     * https://dashboard.stripe.com/webhooks
     * @param Request $request
     * @return void
     */
    public function callback(Request $request): void
    {
        Log::info("====== Start Stripe Callback ======");
        try {
            $requestData = $request->all();
            Log::info("Request data: " . json_encode($requestData));


        } catch (\Exception $e) {
            Log::info("Stripe error: " . $e->getMessage());
        }
    }
}
