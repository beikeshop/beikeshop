<?php
/**
 * Bootstrap.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-17 15:15:27
 * @modified   2023-08-17 15:15:27
 */

namespace Plugin\Stripe;

use Plugin\Stripe\Services\StripeService;
use Stripe\Exception\ApiErrorException;

class Bootstrap
{
    /**
     * https://uniapp.dcloud.net.cn/tutorial/app-payment-stripe.html
     *
     * @throws ApiErrorException
     * @throws \Exception
     */
    public function boot(): void
    {
        add_hook_filter('service.payment.mobile_pay.data', function ($data) {
            $order = $data['order'];
            if ($order->payment_method_code != 'stripe') {
                return $data;
            }

            $data['params'] = (new StripeService($order))->getMobilePaymentData();

            return $data;
        });
    }
}
