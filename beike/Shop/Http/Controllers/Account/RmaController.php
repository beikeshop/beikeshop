<?php
/**
 * RmaController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-03 21:17:04
 * @modified   2022-08-03 21:17:04
 */

namespace Beike\Shop\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Beike\Repositories\RmaReasonRepo;
use Beike\Repositories\RmaRepo;
use Beike\Shop\Http\Requests\RmaRequest;
use Beike\Shop\Services\RmaService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Beike\Repositories\OrderProductRepo;

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
        $data = [
            'rma' => RmaRepo::find($id),
            'statuses' => RmaRepo::getStatuses(),
            'types' => RmaRepo::getTypes(),
        ];
        return view('rms/info', $data);
    }

    public function create(int $orderProductId)
    {
        $data = [
            'orderProduct' => OrderProductRepo::find($orderProductId),
            'statuses' => RmaRepo::getStatuses(),
            'reasons' => RmaReasonRepo::list()->toArray(),
            'types' => RmaRepo::getTypes(),
        ];

        return view('account/rmas/form', $data);
    }

    public function store(RmaRequest $request)
    {
        $rma = RmaService::createFromShop($request->only('order_product_id', 'quantity', 'opened', 'rma_reason_id', 'type', 'comment'));

        return redirect()->to(shop_route('account.rmas.index'))->with('success', '售后服务申请提交成功');
    }
}
