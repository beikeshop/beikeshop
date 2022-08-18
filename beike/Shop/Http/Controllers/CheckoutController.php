<?php
/**
 * CheckoutController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-28 16:47:57
 * @modified   2022-06-28 16:47:57
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Order;
use Beike\Shop\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            $data = (new CheckoutService)->checkoutData();
            return view('checkout', $data);
        } catch (\Exception $e) {
            return redirect(shop_route('carts.index'));
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
        try {
            return (new CheckoutService)->confirm();
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
