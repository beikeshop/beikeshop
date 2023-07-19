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
use Stripe\Stripe;
use Stripe\Token;

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
        Stripe::setApiKey($apiKey);
        $tokenId  = $creditCardData['token'] ?? '';
        if (empty($tokenId)) {
            throw new \Exception('Invalid token');
        }
        $currency = $this->order->currency_code;

        if (! in_array($currency, self::ZERO_DECIMAL)) {
            $total = round($this->order->total, 2) * 100;
        } else {
            $total = floor($this->order->total);
        }

        $stripeChargeParameters = [
            'amount'   => $total,
            'currency' => $currency,
            'metadata' => [
                'orderId' => $this->order->id,
            ],
            'source'   => $tokenId,
            // 'customer' => $this->createCustomer(),
        ];

        $charge = \Stripe\Charge::create($stripeChargeParameters);

        return $charge['paid'] && $charge['captured'];
    }

    /**
     * 创建 stripe customer
     * @return mixed
     * @throws ApiErrorException
     */
    private function createCustomer(): mixed
    {
        $customer = \Stripe\Customer::create([
            'email' => $this->order->email,
        ]);

        return $customer['id'];
    }
}
