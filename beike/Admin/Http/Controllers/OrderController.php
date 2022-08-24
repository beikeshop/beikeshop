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
use Beike\Services\StateMachineService;
use Beike\Shop\Http\Resources\Account\OrderList;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 获取订单列表
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $orders = OrderRepo::filterOrders($request->all());
        $data = [
            'orders' => OrderList::collection($orders),
        ];
        return view('admin::pages.orders.index', $data);
    }


    /**
     * 导出订单列表
     *
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $orders = OrderRepo::filterOrders($request->all());
        $data = [
            'orders' => OrderList::collection($orders),
        ];
        return view('admin::pages.orders.index', $data);
    }


    /**
     * 查看单个订单
     *
     * @param Request $request
     * @param Order $order
     * @return mixed
     * @throws \Exception
     */
    public function show(Request $request, Order $order)
    {
        $order->load(['orderTotals', 'orderHistories']);
        $data = [
            'order' => $order,
            'statuses' => StateMachineService::getInstance($order)->nextBackendStatuses()
        ];
        return view('admin::pages.orders.form', $data);
    }


    /**
     * 更新订单状态,添加订单更新日志
     *
     * @param Request $request
     * @param Order $order
     * @return array
     * @throws \Throwable
     */
    public function updateStatus(Request $request, Order $order)
    {
        $status = $request->get('status');
        $comment = $request->get('comment');
        $notify = $request->get('notify');
        $stateMachine = new StateMachineService($order);
        $stateMachine->changeStatus($status, $comment, $notify);
        return json_success(trans('common.updated_success'));
    }
}
