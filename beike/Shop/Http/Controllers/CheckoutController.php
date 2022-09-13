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

use Illuminate\Http\Request;
use Beike\Shop\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            $data = (new CheckoutService)->checkoutData();
            return view('checkout', $data);
        } catch (\Exception $e) {
            return redirect(shop_route('carts.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * 更改结算信息
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        try {
            $requestData = $request->all();
            return (new CheckoutService)->update($requestData);
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
        return (new CheckoutService)->confirm();
    }
}
