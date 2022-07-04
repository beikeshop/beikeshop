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

use Beike\Shop\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $data = (new CheckoutService)->checkoutData();
        return view('checkout', $data);
    }


    /**
     * 更改结算信息
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        $requestData = $request->all();
        return (new CheckoutService)->update($requestData);
    }


    /**
     * 确认提交订单
     *
     * @param Request $request
     * @return array
     */
    public function confirm(Request $request): array
    {
        return (new CheckoutService)->confirm();
    }
}
