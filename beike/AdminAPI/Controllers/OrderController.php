<?php
/**
 * OrderController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 16:40:35
 * @modified   2023-04-20 16:40:35
 */

namespace Beike\AdminAPI\Controllers;

use Beike\Models\Order;
use Beike\Models\OrderShipment;
use Beike\Repositories\OrderRepo;
use Beike\Services\ShipmentService;
use Beike\Services\StateMachineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController
{
    /**
     * 获取订单列表
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $orders = OrderRepo::filterOrders($request->all());

        return hook_filter('admin_api.order.index.data', $orders);
    }

    /**
     * 查看单个订单
     *
     * @param Request $request
     * @param Order   $order
     * @return mixed
     * @throws \Exception
     */
    public function show(Request $request, Order $order)
    {
        $order->load(['orderTotals', 'orderHistories', 'orderShipments']);
        $data             = hook_filter('admin.order.show.data', ['order' => $order, 'html_items' => []]);
        $data['statuses'] = StateMachineService::getInstance($order)->nextBackendStatuses();

        return hook_filter('admin_api.order.show.data', $data);
    }

    /**
     * 更新订单状态,添加订单更新日志
     *
     * @param Request $request
     * @param Order   $order
     * @return JsonResponse
     * @throws \Throwable
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $status  = $request->get('status');
        $comment = $request->get('comment');
        $notify  = $request->get('notify');

        $shipment = ShipmentService::handleShipment(\request('express_code'), \request('express_number'));

        $stateMachine = new StateMachineService($order);
        $stateMachine->setShipment($shipment)->changeStatus($status, $comment, $notify);

        $orderStatusData = $request->all();

        hook_action('admin.order.update_status.after', $orderStatusData);

        return json_success(trans('common.updated_success'));
    }

    /**
     * 更新发货信息
     */
    public function updateShipment(Request $request, Order $order, int $orderShipmentId): JsonResponse
    {
        $data          = $request->all();
        $orderShipment = OrderShipment::query()->where('order_id', $order->id)->findOrFail($orderShipmentId);
        ShipmentService::updateShipment($orderShipment, $data);
        hook_action('admin.order.update_shipment.after', [
            'request_data' => $data,
            'shipment'     => $orderShipment,
        ]);

        return json_success(trans('common.updated_success'));
    }
}
