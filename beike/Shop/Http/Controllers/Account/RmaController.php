<?php
/**
 * RmaController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
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
        $rma         = RmaRepo::find($id);
        $data        = [
            'rma'          => (new RmaDetail($rma))->jsonSerialize(),
            'orderProduct' => OrderProductRepo::find($rma->order_product_id),
        ];

        return view('account/rmas/info', $data);
    }

    public function create(int $orderProductId)
    {
        $data = [
            'orderProduct' => OrderProductRepo::find($orderProductId),
            'statuses'     => RmaRepo::getStatuses(),
            'reasons'      => RmaReasonDetail::collection(RmaReasonRepo::list())->jsonSerialize(),
            'types'        => RmaRepo::getTypes(),
        ];

        return view('account/rmas/form', $data);
    }

    public function store(RmaRequest $request)
    {
        $rma = RmaService::createFromShop($request->all());

        return redirect()->to(shop_route('account.rma.index'))->with('success', trans('common.success'));
    }
}
