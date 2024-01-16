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

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\RmaDetail;
use Beike\Admin\Http\Resources\RmaHistoryDetail;
use Beike\Repositories\RmaRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RmaController extends Controller
{
    public function index(Request $request)
    {
        $rmas = RmaRepo::list($request->only('name', 'email', 'telephone', 'product_name', 'sku', 'type', 'status'));
        $data = [
            'rmas'        => $rmas,
            'rmas_format' => RmaDetail::collection($rmas)->jsonSerialize(),
        ];

        $data = hook_filter('admin.rma.index.data', $data);

        return view('admin::pages.rmas.index', $data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $rma  = RmaRepo::find($id);
        $data = [
            'rma'       => (new RmaDetail($rma))->jsonSerialize(),
            'histories' => RmaHistoryDetail::collection($rma->histories)->jsonSerialize(),
            'statuses'  => RmaRepo::getStatuses(),
            'types'     => RmaRepo::getTypes(),
        ];

        $data = hook_filter('admin.rma.show.data', $data);

        return view('admin::pages.rmas.info', $data);
    }

    public function addHistory(Request $request, int $id)
    {
        RmaRepo::addHistory($id, $request->only('status', 'notify', 'comment'));
        $data = [
            'rma'      => (new RmaDetail(RmaRepo::find($id)))->jsonSerialize(),
            'statuses' => RmaRepo::getStatuses(),
        ];

        hook_filter('admin.rma.add_history.data', $data);

        return json_success(trans('common.updated_success'), $data);
    }

    public function destroy(int $id): JsonResponse
    {
        RmaRepo::delete($id);

        hook_action('admin.rma.destroy.after', $id);

        return json_success(trans('common.deleted_success'));
    }
}
