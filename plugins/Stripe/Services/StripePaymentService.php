<?php
/**
 * StripePaymentService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 16:09:21
 * @modified   2022-08-08 16:09:21
 */

namespace Plugin\Stripe\Services;

use Beike\Shop\Services\PaymentService;
use Stripe\Exception\ApiErrorException;

class StripePaymentService extends PaymentService
{
    // 零位十进制货币 https://stripe.com/docs/currencies#special-cases
    public const ZERO_DECIMAL = [
        'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA',
        'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF',
    ];

    /**
     * @throws ApiErrorException
     * @throws \Exception
     */
    public function capture($creditCardData): bool
    {
        $apiKey = plugin_setting('stripe.secret_key');
        \Stripe\Stripe::setApiKey($apiKey);
        $tokenId = $creditCardData['token'] ?? '';
        if (empty($tokenId)) {
            throw new \Exception('Invalid token');
        }
        $currency = $this->order->currency_code;

        if (!in_array($currency, self::ZERO_DECIMAL)) {
            $total = round($this->order->total, 2) * 100;
        } else {
            $total = floor($this->order->total);
        }

        $stripeCustomer = $this->createCustomer($tokenId);

        $stripeChargeParameters = [
            'amount' => $total,
            'currency' => $currency,
            'metadata' => [
                'order_number' => $this->order->number,
            ],
            'customer' => $stripeCustomer->id,
            'shipping' => $this->getShippingAddress(),
        ];

        $charge = \Stripe\Charge::create($stripeChargeParameters);

        return $charge['paid'] && $charge['captured'];
    }

    /**
     * 创建 stripe customer
     * @param string $source
     * @return \Stripe\Customer
     * @throws ApiErrorException
     */
    private function createCustomer(string $source = ''): \Stripe\Customer
    {
        return \Stripe\Customer::create([
            'source' => $source,
            'email' => $this->order->email,
            'description' => setting('base.meta_title'),
            'name' => $this->order->customer_name,
            'phone' => $this->order->shipping_telephone,
            'address' => [
                'city' => $this->order->payment_city,
                'country' => $this->order->payment_country,
                'line1' => $this->order->payment_address_1,
                'line2' => $this->order->payment_address_2,
                'postal_code' => $this->order->payment_zipcode,
                'state' => $this->order->payment_zone,
            ],
            'shipping' => $this->getShippingAddress(),
            'metadata' => [
                'order_number' => $this->order->number,
            ],
        ]);
    }

    /**
     * @return array
     */
    private function getShippingAddress():array
    {
        return [
            'name' => $this->order->shipping_customer_name,
            'phone' => $this->order->shipping_telephone,
            'address' => [
                'city' => $this->order->shipping_city,
                'country' => $this->order->shipping_country,
                'line1' => $this->order->shipping_address_1,
                'line2' => $this->order->shipping_address_2,
                'postal_code' => $this->order->shipping_zipcode,
                'state' => $this->order->shipping_zone,
            ],
        ];
    }
}
