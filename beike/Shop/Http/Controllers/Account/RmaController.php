<?php

/**
 * RmaController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-03 21:17:04
 * @modified   2022-08-03 21:17:04
 */

namespace Beike\Shop\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Beike\Repositories\OrderProductRepo;
use Beike\Repositories\RmaReasonRepo;
use Beike\Repositories\RmaRepo;
use Beike\Shop\Http\Requests\RmaRequest;
use Beike\Shop\Http\Resources\Account\RmaReasonDetail;
use Beike\Shop\Http\Resources\RmaDetail;
use Beike\Shop\Services\RmaService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class RmaController extends Controller
{
    public function index()
    {
        $rmas = RmaRepo::listByCustomer(current_customer());

        $data = [
            'rmas' => $rmas,
        ];

        return view('account/rmas/index', $data);
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $rma         = RmaRepo::find($id, ['rmaReason']);
        if (! $rma || $rma->customer_id != current_customer()->id) {
            return abort(404);
        }

        $rmaData     = (new RmaDetail($rma))->jsonSerialize();

        $data        = [
            'rma'          => $rmaData,
            'orderProduct' => OrderProductRepo::find($rma->order_product_id),
        ];

        return view('account/rmas/info', $data);
    }

    /**
     * 标记RMA为已发货
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsShipped(int $id)
    {
        $express_com = request()->input('express_com');
        $express_no  = request()->input('express_no');

        if (empty($express_com) || empty($express_no)) {
            return response()->json(['success' => false, 'message' => trans('shop/account/rma.please_fill_required_fields')]);
        }

        try {
            $rma = RmaRepo::find($id);

            if ($rma->status !== 'approved') {
                return response()->json(['success' => false, 'message' => trans('shop/account/rma.update_failed')]);
            }

            $rma->update([
                'express_com' => $express_com,
                'express_no'  => $express_no,
                'status'      => 'shipped',
            ]);

            return response()->json(['success' => true, 'message' => trans('common.update_success')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => trans('shop/account/rma.update_failed')]);
        }
    }

    public function create(int $orderProductId)
    {
        $rmaQuantity  = RmaRepo::getRmaQuantity($orderProductId);
        $orderProduct = OrderProductRepo::find($orderProductId);
        if (! $orderProduct || $orderProduct->order->customer_id != current_customer()->id) {
            return abort(404);
        }

        $data = [
            'rmaQuantity'  => $rmaQuantity ?? 0,
            'orderProduct' => $orderProduct,
            'statuses'     => RmaRepo::getStatuses(),
            'reasons'      => RmaReasonDetail::collection(RmaReasonRepo::list())->jsonSerialize(),
            'types'        => RmaRepo::getTypes(),
        ];

        return view('account/rmas/form', $data);
    }

    public function store(RmaRequest $request)
    {
        $quantity       = $request->input('quantity');
        $orderProductId = $request->input('order_product_id');

        $orderProduct = RmaService::findCustomerOrderProduct($orderProductId);
        if (! $orderProduct) {
            return abort(404);
        }

        $rmaQuantity  = RmaRepo::getRmaQuantity($orderProductId);
        if ($quantity > $orderProduct->quantity - $rmaQuantity) {
            return redirect()->back()->with('error', trans('rma.quantity_error'));
        }

        $rma = RmaService::createFromShop($request->all());

        return redirect()->to(shop_route('account.rma.index'))->with('success', trans('common.success'));
    }
}
