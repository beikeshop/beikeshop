<?php
/**
 * CheckoutController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-28 16:47:57
 * @modified   2022-06-28 16:47:57
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\OrderRepo;
use Beike\Shop\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            $data = (new CheckoutService)->checkoutData();
            $data = hook_filter('checkout.index.data', $data);

            return view('checkout', $data);
        } catch (\Exception $e) {
            return redirect(shop_route('carts.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * 更改结算信息
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request): mixed
    {
        try {
            $requestData = $request->all();

            $data = (new CheckoutService)->update($requestData);

            return hook_filter('checkout.update.data', $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 确认提交订单
     *
     * @return mixed
     * @throws \Throwable
     */
    public function confirm()
    {
        try {
            $data = (new CheckoutService)->confirm();

            return hook_filter('checkout.confirm.data', $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function success()
    {
        $order_number = request('order_number');

        $customer = current_customer();
        $order    = OrderRepo::getOrderByNumber($order_number, $customer);
        $data     = hook_filter('account.order.show.data', ['order' => $order, 'html_items' => []]);

        return view('checkout/success', $data);
    }
}
