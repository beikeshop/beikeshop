<?php
/**
 * OrderController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-05 10:29:07
 * @modified   2022-07-05 10:29:07
 */

namespace Beike\Shop\Http\Controllers\Account;

use Illuminate\Http\Request;
use Beike\Repositories\OrderRepo;
use Illuminate\Contracts\View\View;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Resources\Account\OrderList;

class OrderController extends Controller
{
    /**
     * 获取当前客户订单列表
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $orders = OrderRepo::getListByCustomer(current_customer());
        $data = [
            'orders' => OrderList::collection($orders),
        ];

        return view('account/order', $data);
    }

    public function success(Request $request): View
    {
        $orders = OrderRepo::getListByCustomer(current_customer());
        $data = [
            'orders' => OrderList::collection($orders),
        ];

        return view('account/order_success', $data);
    }
}
