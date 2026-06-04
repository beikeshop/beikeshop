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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Plugin\Paypal\Services\PaypalService;

class PaypalController
{
    private ?PaypalService $paypalService = null;

    /**
     * Init Paypal
     *
     * @throws \Throwable
     */
    private function initPaypal($order): PaypalService
    {
        $this->paypalService = new PaypalService($order);

        return $this->paypalService;
    }

    /**
     * 创建 paypal 订单
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $data        = $this->getRequestData($request);
        $orderNumber = $this->getOrderNumberFromData($data, $request);

        if ($orderNumber === '') {
            return response()->json(['message' => 'orderNumber is required'], 422);
        }

        $customer      = current_customer();
        $order         = OrderRepo::getOrderByNumber($orderNumber, $customer);
        $paypalService = $this->initPaypal($order);

        if ($paypalService->isNvpApi()) {
            $paypalOrder = $paypalService->createNvpOrder($this->getNvpCheckoutUrls($request, $orderNumber, $data));
        } else {
            $paypalOrder = $paypalService->createOrder();
        }

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
        $data        = $this->getRequestData($request);
        $orderNumber = $this->getOrderNumberFromData($data, $request);

        if ($orderNumber === '') {
            return response()->json(['message' => 'orderNumber is required'], 422);
        }

        $customer      = current_customer();
        $order         = OrderRepo::getOrderByNumber($orderNumber, $customer);
        $paypalService = $this->initPaypal($order);

        OrderPaymentRepo::createOrUpdatePayment($order->id, ['request' => $data]);

        if ($paypalService->isNvpApi() || $this->hasNvpCaptureData($data, $request)) {
            $token   = $this->getNvpToken($data, $request);
            $payerId = $this->getNvpPayerId($data, $request);

            if ($token === '' || $payerId === '') {
                return response()->json(['message' => 'PayPal token and payer id are required'], 422);
            }

            $result = $paypalService->captureNvpPayment($token, $payerId);
        } else {
            $paypalOrderId = (string) ($data['paypalOrderId'] ?? $data['paypal_order_id'] ?? '');

            if ($paypalOrderId === '') {
                return response()->json(['message' => 'paypalOrderId is required'], 422);
            }

            if ($paypalService->paypalClient === null) {
                return response()->json(['message' => 'PayPal REST client is not initialized'], 500);
            }

            $result = $paypalService->paypalClient->capturePaymentOrder($paypalOrderId);
        }

        OrderPaymentRepo::createOrUpdatePayment($order->id, ['response' => $result]);
        $this->markOrderPaid($paypalService, $order, $result);

        return response()->json($result);
    }

    public function nvpReturn(Request $request): RedirectResponse
    {
        $data        = $request->all();
        $orderNumber = $this->getOrderNumberFromData($data, $request);
        $token       = $this->getNvpToken($data, $request);
        $payerId     = $this->getNvpPayerId($data, $request);

        if ($orderNumber === '' || $token === '' || $payerId === '') {
            return $this->redirectWithMessage($request, $this->getFailureRedirectUrl($orderNumber), 'error', 'Paypal payment failed.');
        }

        try {
            $customer      = current_customer();
            $order         = OrderRepo::getOrderByNumber($orderNumber, $customer);
            $paypalService = $this->initPaypal($order);

            OrderPaymentRepo::createOrUpdatePayment($order->id, ['request' => $data]);
            $result = $paypalService->captureNvpPayment($token, $payerId);
            OrderPaymentRepo::createOrUpdatePayment($order->id, ['response' => $result]);
            $this->markOrderPaid($paypalService, $order, $result);

            $paid = $paypalService->isPaymentSuccessful($result);

            return $this->redirectWithMessage(
                $request,
                $paid ? $this->getSuccessRedirectUrl($orderNumber) : $this->getFailureRedirectUrl($orderNumber),
                $paid ? 'success' : 'error',
                $paid ? 'Paypal payment completed.' : 'Paypal payment is not completed.'
            );
        } catch (\Throwable $e) {
            return $this->redirectWithMessage($request, $this->getFailureRedirectUrl($orderNumber), 'error', 'Paypal payment failed.');
        }
    }

    public function nvpCancel(Request $request): RedirectResponse
    {
        $data        = $request->all();
        $orderNumber = $this->getOrderNumberFromData($data, $request);

        return $this->redirectWithMessage($request, $this->getFailureRedirectUrl($orderNumber), 'error', 'Paypal payment was cancelled.');
    }

    public function nvpNotify(Request $request)
    {
        return response('OK');
    }

    private function markOrderPaid(PaypalService $paypalService, $order, array $result): void
    {
        DB::beginTransaction();

        try {
            if ($paypalService->isPaymentSuccessful($result)) {
                StateMachineService::getInstance($order)->changeStatus(StateMachineService::PAID);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getRequestData(Request $request): array
    {
        $data = \json_decode($request->getContent(), true);

        return is_array($data) ? $data : $request->all();
    }

    private function getNvpCheckoutUrls(Request $request, string $orderNumber, array $data): array
    {
        return [
            'return_url' => (string) ($data['returnUrl'] ?? $data['return_url'] ?? $this->getCallbackUrl('return', $orderNumber)),
            'cancel_url' => (string) ($data['cancelUrl'] ?? $data['cancel_url'] ?? $this->getCallbackUrl('cancel', $orderNumber)),
            'notify_url' => (string) ($data['notifyUrl'] ?? $data['notify_url'] ?? $this->getCallbackUrl('notify', $orderNumber)),
        ];
    }

    private function getCallbackUrl(string $action, string $orderNumber): string
    {
        $routeName  = 'paypal.nvp.' . $action;
        $parameters = ['orderNumber' => $orderNumber];

        if (Route::has($routeName)) {
            return route($routeName, $parameters);
        }

        return url('/paypal/nvp/' . $action) . '?' . http_build_query($parameters);
    }

    private function getOrderNumberFromData(array $data, Request $request): string
    {
        return trim((string) ($data['orderNumber'] ?? $data['order_number'] ?? $request->input('orderNumber') ?? $request->input('order_number') ?? ''));
    }

    private function hasNvpCaptureData(array $data, Request $request): bool
    {
        return $this->getNvpToken($data, $request) !== '' || $this->getNvpPayerId($data, $request) !== '';
    }

    private function getNvpToken(array $data, Request $request): string
    {
        return trim((string) ($data['token'] ?? $data['paypalToken'] ?? $request->input('token', '')));
    }

    private function getNvpPayerId(array $data, Request $request): string
    {
        return trim((string) ($data['PayerID'] ?? $data['payerId'] ?? $data['payer_id'] ?? $request->input('PayerID') ?? $request->input('payerId') ?? $request->input('payer_id') ?? ''));
    }

    private function getSuccessRedirectUrl(string $orderNumber): string
    {
        return $this->buildRedirectUrl('/checkout/success', $orderNumber);
    }

    private function getFailureRedirectUrl(string $orderNumber): string
    {
        return $this->buildRedirectUrl('/checkout/payment', $orderNumber);
    }

    private function buildRedirectUrl(string $path, string $orderNumber): string
    {
        $url = url($path);

        if ($orderNumber !== '') {
            $url .= '?' . http_build_query(['orderNumber' => $orderNumber]);
        }

        return $url;
    }

    private function redirectWithMessage(Request $request, string $url, string $key, string $message): RedirectResponse
    {
        $response = redirect($url);

        if ($request->hasSession()) {
            $response->with($key, $message);
        }

        return $response;
    }
}
