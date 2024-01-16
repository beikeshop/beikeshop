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

namespace Plugin\Paypal;

use Plugin\Paypal\Services\PaypalService;
use Stripe\Exception\ApiErrorException;

class Bootstrap
{
    /**
     * https://uniapp.dcloud.net.cn/tutorial/app-payment-paypal.html
     *
     * @throws ApiErrorException
     * @throws \Exception
     * @throws \Throwable
     */
    public function boot(): void
    {
        add_hook_filter('service.payment.mobile_pay.data', function ($data) {
            $order = $data['order'];
            if ($order->payment_method_code != 'paypal') {
                return $data;
            }

            $data['params'] = (new PaypalService($order))->getMobilePaymentData();

            return $data;
        });
    }
}
