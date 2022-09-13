<?php
/**
 * StripeController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 15:58:36
 * @modified   2022-08-08 15:58:36
 */

namespace Plugin\Stripe\Controllers;

use Beike\Repositories\OrderRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Services\PaymentService;
use Illuminate\Http\Request;
use Plugin\Stripe\Services\StripePaymentService;

class StripeController extends Controller
{
    /**
     * 订单支付扣款
     *
     * @param Request $request
     * @return array
     */
    public function capture(Request $request): array
    {
        try {
            $number = request('order_number');
            $customer = current_customer();
            $order = OrderRepo::getOrderByNumber($number, $customer);
            $creditCardData = $request->all();
            $result = (new StripePaymentService($order))->capture($creditCardData);
            if ($result) {
                $order->status = 'paid';
                $order->save();
                return json_success('支付成功');
            } else {
                return json_success('支付失败');
            }
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
