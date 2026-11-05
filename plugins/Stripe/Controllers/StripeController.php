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
    public function createPaymentIntent(Request $request): JsonResponse
    {
        try {
            $order         = $this->getPayableOrder($request->input('order_number'));
            $paymentIntent = (new StripeService($order))->createWebPaymentIntent();
            OrderPaymentRepo::createOrUpdatePayment($order->id, [
                'transaction_id' => $paymentIntent->id,
                'request'        => ['payment_intent_id' => $paymentIntent->id],
            ]);

            return json_success('', ['client_secret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

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
            $order           = $this->getPayableOrder($request->input('order_number'), true);
            $creditCardData  = $request->all();
            $paymentIntentId = StripeService::getPaymentIntentId($creditCardData);
            if (in_array($order->status, ['paid', 'shipped', 'completed'], true)) {
                return $paymentIntentId && $order->orderPayments()->where('transaction_id', $paymentIntentId)->exists()
                    ? json_success(trans('Stripe::common.capture_success'))
                    : json_fail(trans('Stripe::common.capture_fail'));
            }
            $service = new StripeService($order);

            $result      = $service->capture($creditCardData);
            $paymentData = [
                'request'        => $creditCardData,
                'response'       => ['verified' => $result],
            ];
            if ($result) {
                $paymentData['transaction_id'] = $paymentIntentId;
            }
            OrderPaymentRepo::createOrUpdatePayment($order->id, $paymentData);

            if ($result) {
                StateMachineService::getInstance($order)->setShipment()->changeStatus(StateMachineService::PAID);

                return json_success(trans('Stripe::common.capture_success'));
            }

            return json_fail(trans('Stripe::common.capture_fail'));

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
            if (! app('plugin')->checkActive('stripe')) {
                return json_fail(trans('shop/order.invalid_order'), [], 400);
            }
            $webhookSecret = plugin_setting('stripe.webhook_secret');
            if (empty($webhookSecret)) {
                Log::warning('Stripe webhook secret is not configured');

                return json_fail('Stripe webhook secret is not configured', [], 500);
            }

            $signature = $request->header('Stripe-Signature', '');
            if (empty($signature)) {
                return json_fail('Invalid Stripe signature', [], 400);
            }

            $event       = Webhook::constructEvent($request->getContent(), $signature, $webhookSecret);
            $requestData = $event->toArray();

            Log::info('Request data: ' . json_encode($requestData));

            $type        = $requestData['type']                                       ?? '';
            $orderNumber = $requestData['data']['object']['metadata']['order_number'] ?? '';
            $order       = OrderRepo::getOrderByNumber($orderNumber);

            Log::info('Request type: ' . $type);
            Log::info('Request number: ' . $orderNumber);

            $intentId            = $requestData['data']['object']['id'] ?? '';
            $matchesOrderPayment = $order
                && $order->payment_method_code === 'stripe'
                && $order->orderPayments()->where('transaction_id', $intentId)->exists();
            if ($type === 'payment_intent.succeeded' && $matchesOrderPayment) {
                if ($order->status === 'unpaid' && (new StripeService($order))->capture(['payment_intent_id' => $intentId])) {
                    OrderPaymentRepo::createOrUpdatePayment($order->id, ['callback' => $requestData]);
                    StateMachineService::getInstance($order)->setShipment()->changeStatus(StateMachineService::PAID);
                }

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

    private function getPayableOrder($number, bool $allowPaid = false)
    {
        if (! app('plugin')->checkActive('stripe')) {
            throw new \Exception(trans('shop/order.invalid_order'));
        }
        $customer = current_customer();
        $order    = OrderRepo::getOrderByNumber($number, $customer);

        $statuses = $allowPaid ? ['unpaid', 'paid', 'shipped', 'completed'] : ['unpaid'];
        if (! $order || $order->payment_method_code !== 'stripe' || ! in_array($order->status, $statuses, true)) {
            throw new \Exception(trans('shop/order.invalid_order'));
        }
        if ($customer && (int) $order->customer_id !== (int) $customer->id) {
            throw new \Exception(trans('shop/order.invalid_order'));
        }

        if (! $customer && ! OrderRepo::isGuestOrderAuthorized($order)) {
            throw new \Exception(trans('shop/order.invalid_order'));
        }

        return $order;
    }
}
