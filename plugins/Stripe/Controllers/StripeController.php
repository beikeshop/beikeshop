<?php

/**
 * StripeController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
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
use Plugin\Stripe\Services\StripeService;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

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
            $result = (new StripeService($order))->capture($creditCardData);
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
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        Log::info('====== Start Stripe Callback ======');

        try {
            $webhookSecret = plugin_setting('stripe.webhook_secret');
            if (empty($webhookSecret)) {
                Log::warning('Stripe webhook secret is not configured');

                return json_fail('Stripe webhook secret is not configured', [], 500);
            }

            $payload   = $request->getContent();
            $signature = $request->header('Stripe-Signature', '');
            if (empty($signature)) {
                Log::warning('Stripe callback missing signature header');

                return json_fail('Invalid Stripe signature', [], 400);
            }

            $event       = Webhook::constructEvent($payload, $signature, $webhookSecret);
            $requestData = $event->toArray();
            Log::info('Request data: ' . json_encode($requestData));

            $type        = $requestData['type'] ?? '';
            $orderNumber = $requestData['data']['object']['metadata']['order_number'] ?? '';
            $order       = OrderRepo::getOrderByNumber($orderNumber);

            Log::info('Request type: ' . $type);
            Log::info('Request number: ' . $orderNumber);

            if ($type == 'charge.succeeded' && $order) {
                StateMachineService::getInstance($order)->setShipment()->changeStatus(StateMachineService::PAID);

                return json_success(trans('Stripe::common.capture_success'));
            }

            return json_success(trans('Stripe::common.capture_fail'));

        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            Log::warning('Stripe signature verification failed: ' . $e->getMessage());

            return json_fail('Invalid Stripe signature', [], 400);
        } catch (\Exception $e) {
            Log::info('Stripe error: ' . $e->getMessage());

            return json_fail($e->getMessage(), [], 500);
        }
    }
}
