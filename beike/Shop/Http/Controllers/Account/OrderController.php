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
use Illuminate\Contracts\View\Factory;
use Beike\Shop\Services\PaymentService;
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

    /**
     * 获取当前客户订单列表
     *
     * @param Request $request
     * @param $number
     * @return View
     */
    public function show(Request $request, $number): View
    {
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($number, $customer);
        return view('account/order_info', ['order' => $order]);
    }


    /**
     * 订单提交成功页
     *
     * @param Request $request
     * @param $number
     * @return View
     */
    public function success(Request $request, $number): View
    {
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($number, $customer);
        return view('account/order_success', ['order' => $order]);
    }


    /**
     * 订单支付页面
     *
     * @param Request $request
     * @param $number
     * @return Factory|View
     * @throws \Exception
     */
    public function pay(Request $request, $number)
    {
        $customer = current_customer();
        $order = OrderRepo::getOrderByNumber($number, $customer);
        return (new PaymentService($order))->pay();
    }


    /**
     * 订单支付页面
     *
     * @param Request $request
     * @param $number
     * @return array
     * @throws \Exception
     */
    public function capture(Request $request, $number): array
    {
        try {
            $customer = current_customer();
            $order = OrderRepo::getOrderByNumber($number, $customer);
            $creditCardData = $request->all();
            $result = (new PaymentService($order))->capture($creditCardData);
            if ($result) {
                $order->status = 'paid';
                $order->save();
                return json_success('支付成功');
            } else {
                return json_success('支付失败');
            }
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
