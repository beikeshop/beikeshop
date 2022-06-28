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

use Beike\Shop\Services\CartService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $carts = CartService::list(current_customer());
        $amount = collect($carts)->sum('subtotal');
        $data = [
            'carts' => $carts,
            'quantity' => collect($carts)->sum('quantity'),
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];
        return view('checkout', $data);
    }
}
