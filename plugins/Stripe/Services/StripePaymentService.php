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

use Stripe\Token;
use Stripe\Stripe;
use Beike\Shop\Services\PaymentService;
use Stripe\Exception\ApiErrorException;

class StripePaymentService extends PaymentService
{
    /**
     * @throws ApiErrorException
     */
    public function capture($creditCardData): bool
    {
        $apiKey = plugin_setting('stripe.secret_key');
        Stripe::setApiKey($apiKey);
        $token = Token::create([
            'card' => [
                'number' => $creditCardData['cardnum'],
                'exp_year' => $creditCardData['year'],
                'exp_month' => $creditCardData['month'],
                'cvc' => $creditCardData['cvv'],
            ],
        ]);

        // $customer = Customer::create([
        //     'email' => $this->order->email,
        // ]);
        // $customerId = $customer['id'];

        $tokenId = $token['id'];
        $total = round($this->order->total, 2) * 100;
        $stripeChargeParameters = array(
            'amount' => $total,
            'currency' => $this->order->currency_code,
            'metadata' => array(
                'orderId' => $this->order->id,
            ),
            'source' => $tokenId,
            // 'customer' => $customerId,
        );

        $charge = \Stripe\Charge::create($stripeChargeParameters);
        return $charge['paid'] && $charge['captured'];
    }
}
