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

    public function update(Request $request)
    {
        $requestData = $request->all();
        $data = (new CheckoutService)->update($requestData);
        return view('checkout', $data);
    }

    public function confirm(Request $request)
    {
        $data = (new CheckoutService)->confirm();
        return view('checkout', $data);
    }
}
