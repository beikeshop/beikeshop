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
use Illuminate\Support\Facades\Route;
use Srmklive\PayPal\Services\PayPal;

class PaypalService extends PaymentService
{
    private const API_MODE_REST = 'rest';
    private const API_MODE_NVP = 'nvp';

    public ?PayPal $paypalClient = null;

    private ?PaypalNvpClient $nvpClient = null;

    /**
     * @param             $order
     * @throws \Exception
     * @throws \Throwable
     */
    public function __construct($order)
    {
        parent::__construct($order);

        if (! $this->isNvpApi()) {
            $this->initPaypal();
        }
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function initPaypal(): void
    {
        $paypalSetting = $this->getPaypalSetting();
        $mode          = $this->getPaypalEnvironment($paypalSetting);
        $config        = [
            'mode'    => $mode,
            'sandbox' => [
                'client_id'     => $paypalSetting['sandbox_client_id'] ?? '',
                'client_secret' => $paypalSetting['sandbox_secret'] ?? '',
            ],
            'live' => [
                'client_id'     => $paypalSetting['live_client_id'] ?? '',
                'client_secret' => $paypalSetting['live_secret'] ?? '',
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
        $paypalSetting = $this->getPaypalSetting();
        $mode          = $this->getPaypalEnvironment($paypalSetting);

        if ($this->isNvpApi()) {
            $paypalOrder = $this->createNvpOrder();

            return [
                'apiMode'     => self::API_MODE_NVP,
                'clientId'    => '',
                'currency'    => $this->getOrderCurrency(),
                'environment' => $mode,
                'orderId'     => $paypalOrder['id'] ?? null,
                'token'       => $paypalOrder['token'] ?? null,
                'approvalUrl' => $paypalOrder['approval_url'] ?? null,
                'userAction'  => 'paynow',
            ];
        }

        if ($mode === 'sandbox') {
            $clientId = $paypalSetting['sandbox_client_id'] ?? '';
        } else {
            $clientId = $paypalSetting['live_client_id'] ?? '';
        }

        $paypalOrder = $this->createOrder();

        return [
            'apiMode'     => self::API_MODE_REST,
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
        if ($this->isNvpApi()) {
            return $this->createNvpOrder();
        }

        if ($this->paypalClient === null) {
            $this->initPaypal();
        }

        $order = $this->order;
        $total = $this->formatAmount($order->total);

        return $this->paypalClient->createOrder([
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $this->getOrderCurrency(),
                        'value'         => $total,
                    ],
                    'description' => $this->getOrderDescription(),
                ],
            ],
        ]);
    }

    public function createNvpOrder(array $urls = []): array
    {
        $orderNumber = $this->getOrderNumber();
        $total       = $this->formatAmount($this->order->total);
        $currency    = $this->getOrderCurrency();
        $returnUrl   = $urls['return_url'] ?? $urls['returnUrl'] ?? $this->defaultNvpReturnUrl($orderNumber);
        $cancelUrl   = $urls['cancel_url'] ?? $urls['cancelUrl'] ?? $this->defaultNvpCancelUrl($orderNumber);
        $notifyUrl   = $urls['notify_url'] ?? $urls['notifyUrl'] ?? $this->defaultNvpNotifyUrl($orderNumber);

        $parameters = [
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_AMT'           => $total,
            'PAYMENTREQUEST_0_CURRENCYCODE'  => $currency,
            'PAYMENTREQUEST_0_DESC'          => $this->getOrderDescription(),
            'RETURNURL'                      => $returnUrl,
            'CANCELURL'                      => $cancelUrl,
            'NOSHIPPING'                     => '1',
            'REQCONFIRMSHIPPING'             => '0',
            'SOLUTIONTYPE'                   => 'Sole',
            'LANDINGPAGE'                    => 'Billing',
        ];

        if ($orderNumber !== '') {
            $parameters['PAYMENTREQUEST_0_INVNUM'] = $orderNumber;
            $parameters['PAYMENTREQUEST_0_CUSTOM'] = $orderNumber;
        }

        if ($notifyUrl !== '') {
            $parameters['PAYMENTREQUEST_0_NOTIFYURL'] = $notifyUrl;
        }

        $result      = $this->getNvpClient()->setExpressCheckout($parameters);
        $token       = $result['TOKEN'];
        $approvalUrl = $result['CHECKOUT_URL'];

        return [
            'id'           => $token,
            'token'        => $token,
            'status'       => strtoupper((string) ($result['ACK'] ?? '')),
            'approval_url' => $approvalUrl,
            'links'        => [
                [
                    'href'   => $approvalUrl,
                    'rel'    => 'approve',
                    'method' => 'GET',
                ],
            ],
            'raw' => $result,
        ];
    }

    public function captureNvpPayment(string $token, string $payerId): array
    {
        $token   = trim($token);
        $payerId = trim($payerId);

        if ($token === '' || $payerId === '') {
            throw new \InvalidArgumentException('PayPal token and payer id are required.');
        }

        $details = $this->getNvpClient()->getExpressCheckoutDetails($token);
        $this->validateNvpDetails($details);

        if (! empty($details['PAYERID']) && (string) $details['PAYERID'] !== $payerId) {
            throw new \RuntimeException('PayPal payer id does not match checkout details.');
        }

        $parameters = [
            'TOKEN'                          => $token,
            'PAYERID'                        => $payerId,
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_AMT'           => $this->formatAmount($this->order->total),
            'PAYMENTREQUEST_0_CURRENCYCODE'  => $this->getOrderCurrency(),
            'PAYMENTREQUEST_0_DESC'          => $this->getOrderDescription(),
        ];

        $orderNumber = $this->getOrderNumber();
        if ($orderNumber !== '') {
            $parameters['PAYMENTREQUEST_0_INVNUM'] = $orderNumber;
        }

        $response = $this->getNvpClient()->doExpressCheckoutPayment($parameters);

        return [
            'id'       => $response['PAYMENTINFO_0_TRANSACTIONID'] ?? null,
            'status'   => $this->normalizeNvpPaymentStatus($response),
            'token'    => $token,
            'payer_id' => $payerId,
            'details'  => $details,
            'response' => $response,
        ];
    }

    public function isPaymentSuccessful(array $result): bool
    {
        $status   = strtoupper((string) ($result['status'] ?? $result['PAYMENTINFO_0_PAYMENTSTATUS'] ?? ''));
        $response = $result['response'] ?? [];

        if ($status === '' && is_array($response)) {
            $status = strtoupper((string) ($response['PAYMENTINFO_0_PAYMENTSTATUS'] ?? $response['PAYMENTSTATUS'] ?? ''));
        }

        return $status === 'COMPLETED';
    }

    public function isNvpApi(): bool
    {
        return $this->getApiMode() === self::API_MODE_NVP;
    }

    public function getApiMode(): string
    {
        $paypalSetting = $this->getPaypalSetting();
        $mode          = strtolower(trim((string) $this->getSettingValue($paypalSetting, ['api_mode', 'api_standard', 'paypal_api_mode'], self::API_MODE_REST)));
        $mode          = str_replace([' ', '-'], ['_', '_'], $mode);

        return in_array($mode, ['nvp', 'soap', 'nvp_soap', 'nvp/soap'], true) ? self::API_MODE_NVP : self::API_MODE_REST;
    }

    private function getNvpClient(): PaypalNvpClient
    {
        if ($this->nvpClient instanceof PaypalNvpClient) {
            return $this->nvpClient;
        }

        $paypalSetting = $this->getPaypalSetting();
        $mode          = $this->getPaypalEnvironment($paypalSetting);
        $credentials   = $this->getNvpCredentials($paypalSetting, $mode);
        $timeout       = (int) $this->getSettingValue($paypalSetting, ['nvp_timeout', 'api_timeout'], 30);

        return $this->nvpClient = new PaypalNvpClient([
            'mode'      => $mode,
            'username'  => $credentials['username'],
            'password'  => $credentials['password'],
            'signature' => $credentials['signature'],
            'timeout'   => $timeout,
        ]);
    }

    private function getNvpCredentials(array $paypalSetting, string $mode): array
    {
        $prefix = $mode === 'sandbox' ? 'sandbox' : 'live';

        return [
            'username' => (string) $this->getSettingValue($paypalSetting, [
                'nvp_' . $prefix . '_username',
                $prefix . '_nvp_username',
                $prefix . '_api_username',
                'api_' . $prefix . '_username',
                $prefix . '_username',
                'nvp_username',
                'api_username',
                'username',
            ]),
            'password' => (string) $this->getSettingValue($paypalSetting, [
                'nvp_' . $prefix . '_password',
                $prefix . '_nvp_password',
                $prefix . '_api_password',
                'api_' . $prefix . '_password',
                $prefix . '_password',
                'nvp_password',
                'api_password',
                'password',
            ]),
            'signature' => (string) $this->getSettingValue($paypalSetting, [
                'nvp_' . $prefix . '_signature',
                $prefix . '_nvp_signature',
                $prefix . '_api_signature',
                'api_' . $prefix . '_signature',
                $prefix . '_signature',
                'nvp_signature',
                'api_signature',
                'signature',
            ]),
        ];
    }

    private function normalizeNvpPaymentStatus(array $response): string
    {
        $status = strtoupper((string) ($response['PAYMENTINFO_0_PAYMENTSTATUS'] ?? $response['PAYMENTSTATUS'] ?? ''));

        if ($status !== '') {
            return $status;
        }

        return strtoupper((string) ($response['ACK'] ?? 'FAILED'));
    }

    private function validateNvpDetails(array $details): void
    {
        $amount = $details['PAYMENTREQUEST_0_AMT'] ?? $details['AMT'] ?? null;

        if ($amount === null || $this->formatAmount($amount) !== $this->formatAmount($this->order->total)) {
            throw new \RuntimeException('PayPal payment amount does not match the order amount.');
        }

        $currency = $details['PAYMENTREQUEST_0_CURRENCYCODE'] ?? $details['CURRENCYCODE'] ?? null;

        if ($currency === null || strtoupper((string) $currency) !== $this->getOrderCurrency()) {
            throw new \RuntimeException('PayPal payment currency does not match the order currency.');
        }

        $invoiceNumber = $details['PAYMENTREQUEST_0_INVNUM'] ?? $details['INVNUM'] ?? null;
        $orderNumber   = $this->getOrderNumber();

        if ($invoiceNumber !== null && $orderNumber !== '' && (string) $invoiceNumber !== $orderNumber) {
            throw new \RuntimeException('PayPal invoice number does not match the order number.');
        }
    }

    private function getPaypalSetting(): array
    {
        $paypalSetting = plugin_setting('paypal');

        return is_array($paypalSetting) ? $paypalSetting : [];
    }

    private function getPaypalEnvironment(?array $paypalSetting = null): string
    {
        $paypalSetting = $paypalSetting ?? $this->getPaypalSetting();

        return ! empty($paypalSetting['sandbox_mode']) ? 'sandbox' : 'live';
    }

    private function getSettingValue(array $settings, array $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $settings) && $settings[$key] !== null && $settings[$key] !== '') {
                return $settings[$key];
            }
        }

        return $default;
    }

    private function defaultNvpReturnUrl(string $orderNumber): string
    {
        return $this->buildCallbackUrl('paypal.nvp.return', '/paypal/nvp/return', $orderNumber);
    }

    private function defaultNvpCancelUrl(string $orderNumber): string
    {
        return $this->buildCallbackUrl('paypal.nvp.cancel', '/paypal/nvp/cancel', $orderNumber);
    }

    private function defaultNvpNotifyUrl(string $orderNumber): string
    {
        return $this->buildCallbackUrl('paypal.nvp.notify', '/paypal/nvp/notify', $orderNumber);
    }

    private function buildCallbackUrl(string $routeName, string $path, string $orderNumber): string
    {
        $parameters = ['orderNumber' => $orderNumber];

        if (Route::has($routeName)) {
            return route($routeName, $parameters);
        }

        return url($path) . '?' . http_build_query($parameters);
    }

    private function getOrderCurrency(): string
    {
        $currency = $this->order->currency_code ?? system_setting('base.currency');

        return strtoupper((string) $currency);
    }

    private function formatAmount($amount): string
    {
        return number_format(round((float) $amount, 2), 2, '.', '');
    }

    private function getOrderDescription(): string
    {
        if (method_exists($this->order, 'getOrderDesc')) {
            $description = (string) $this->order->getOrderDesc(127);
        } else {
            $description = 'Order ' . $this->getOrderNumber();
        }

        $description = trim($description);

        if ($description === '') {
            $description = 'Order ' . $this->getOrderNumber();
        }

        return $this->limitString($description, 127);
    }

    private function getOrderNumber(): string
    {
        foreach (['number', 'order_number', 'order_no'] as $key) {
            $value = $this->order->{$key} ?? null;

            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }

        if (($this->order->id ?? null) !== null) {
            return (string) $this->order->id;
        }

        return '';
    }

    private function limitString(string $value, int $length): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, $length, 'UTF-8');
        }

        return substr($value, 0, $length);
    }
}
