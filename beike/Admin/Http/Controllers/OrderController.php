<?php
/**
 * OrderController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-05 10:45:26
 * @modified   2022-07-05 10:45:26
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Order;
use Beike\Repositories\OrderRepo;
use Beike\Shop\Http\Resources\Account\OrderList;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = OrderRepo::getListAll();
        $data = [
            'orders' => OrderList::collection($orders),
        ];
        return view('admin::account/order', $data);
    }

    public function show(Request $request, Order $order)
    {
        dd($order);
    }
}
