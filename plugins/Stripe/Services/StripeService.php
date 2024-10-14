<?php
/**
 * StripeService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 16:09:21
 * @modified   2022-08-08 16:09:21
 */

namespace Plugin\Stripe\Services;

use Beike\Models\Country;
use Beike\Shop\Services\PaymentService;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeService extends PaymentService
{
    // 零位十进制货币 https://stripe.com/docs/currencies#special-cases
    public const ZERO_DECIMAL = [
        'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA',
        'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF',
    ];

    private StripeClient $stripeClient;

    /**
     * @throws \Exception
     */
    public function __construct($order)
    {
        parent::__construct($order);
        $apiKey = plugin_setting('stripe.secret_key');
        if (empty($apiKey)) {
            throw new \Exception('Invalid stripe secret key');
        }
        $this->stripeClient = new StripeClient($apiKey);
    }

    /**
     * @throws ApiErrorException
     * @throws \Exception
     */
    public function capture($creditCardData): bool
    {
        // web
        if (isset($creditCardData['token'])) {
            $tokenId = $creditCardData['token'] ?? '';
            $currency = $this->order->currency_code;

            if (! in_array($currency, self::ZERO_DECIMAL)) {
                $total = round($this->order->total, 2) * 100;
            } else {
                $total = floor($this->order->total);
            }

            $stripeCustomer = $this->createCustomer($tokenId);

            $stripeChargeParameters = [
                'amount'   => $total,
                'currency' => $currency,
                'metadata' => [
                    'order_number' => $this->order->number,
                ],
                'customer' => $stripeCustomer->id,
                'shipping' => $this->getShippingAddress(),
            ];

            // $charge = \Stripe\Charge::create($stripeChargeParameters);
            $charge = $this->stripeClient->charges->create($stripeChargeParameters);

            return $charge['paid'] && $charge['captured'];
        } else {
            // app
            // 从返回的 client_secret 中提取 payment_intent_id
            $clientSecret = $creditCardData['paymentIntent'] ?? '';
            if (empty($clientSecret)) {
                throw new \Exception('Invalid paymentIntent');
            }

            // 提取 payment_intent_id (前缀到第一个 '_secret' 之前的部分)
            $paymentIntentId = substr($clientSecret, 0, strpos($clientSecret, '_secret'));

            // 检查是否成功提取 payment_intent_id
            if (empty($paymentIntentId)) {
                throw new \Exception('Invalid paymentIntent ID');
            }

            // 根据 PaymentIntent ID 查询支付状态
            $paymentIntent = $this->stripeClient->paymentIntents->retrieve($paymentIntentId);

            // 检查支付状态
            if ($paymentIntent->status === 'succeeded') {
                return true;
            }

            return false;
        }
    }

    /**
     * 创建 stripe customer
     * @param string $source
     * @return Customer
     * @throws ApiErrorException
     */
    private function createCustomer(string $source = ''): Customer
    {
        $paymentCountry = Country::query()->find($this->order->payment_country_id);
        $customerData   = [
            'email'       => $this->order->email,
            'description' => setting('base.meta_title'),
            'name'        => $this->order->customer_name,
            'phone'       => $this->order->shipping_telephone,
            'address'     => [
                'city'        => $this->order->payment_city,
                'country'     => $paymentCountry->code ?? '',
                'line1'       => $this->order->payment_address_1,
                'line2'       => $this->order->payment_address_2,
                'postal_code' => $this->order->payment_zipcode,
                'state'       => $this->order->payment_zone,
            ],
            'shipping' => $this->getShippingAddress(),
            'metadata' => [
                'order_number' => $this->order->number,
            ],
        ];

        if ($source) {
            $customerData['source'] = $source;
        }

        return $this->stripeClient->customers->create($customerData);
    }

    /**
     * @return array
     */
    private function getShippingAddress(): array
    {
        $shippingCountry = Country::query()->find($this->order->shipping_country_id);

        return [
            'name'    => $this->order->shipping_customer_name,
            'phone'   => $this->order->shipping_telephone,
            'address' => [
                'city'        => $this->order->shipping_city,
                'country'     => $shippingCountry->code ?? '',
                'line1'       => $this->order->shipping_address_1,
                'line2'       => $this->order->shipping_address_2,
                'postal_code' => $this->order->shipping_zipcode,
                'state'       => $this->order->shipping_zone,
            ],
        ];
    }

    /**
     * 获取支付参数给 uniapp 使用
     * @return array
     * @throws ApiErrorException
     */
    public function getMobilePaymentData(): array
    {
        $stripeCustomer = $this->createCustomer();
        $paymentIntent  = $this->createPaymentIntent($stripeCustomer);

        return [
            'isAllowDelay'   => true,
            'merchantName'   => system_setting('base.meta_title'),
            'paymentIntent'  => $paymentIntent->client_secret,
            'publishKey'     => plugin_setting('stripe.publishable_key'),
            'billingDetails' => $this->getBillingDetails(),
        ];
    }

    /**
     * 获取支付地址
     *
     * @return array
     */
    private function getBillingDetails(): array
    {
        $order          = $this->order;
        $paymentCountry = Country::query()->find($order->payment_country_id);

        return [
            'name'    => $order->customer_name,
            'email'   => $order->email,
            'phone'   => $order->telephone ?: $order->payment_telephone,
            'address' => [
                'city'       => $order->payment_city,
                'country'    => $paymentCountry->code ?? '',
                'line1'      => $order->payment_address_1,
                'line2'      => $order->payment_address_2,
                'postalCode' => $order->payment_zipcode,
                'state'      => $order->payment_zone,
            ],
        ];
    }

    /**
     * Create payment intent
     * @param $stripeCustomer
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPaymentIntent($stripeCustomer): PaymentIntent
    {
        $currency = $this->order->currency_code;
        if (! in_array($currency, self::ZERO_DECIMAL)) {
            $total = round($this->order->total, 2) * 100;
        } else {
            $total = floor($this->order->total);
        }

        return $this->stripeClient->paymentIntents->create([
            'amount'                    => $total,
            'currency'                  => $currency,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'customer' => $stripeCustomer->id,
            'metadata' => [
                'order_number' => $this->order->number,
            ],
            'shipping' => $this->getShippingAddress(),
        ]);
    }
}
