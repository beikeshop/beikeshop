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

use Beike\Admin\Http\Resources\RmaReasonDetail;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\RmaReasonRepo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RmaReasonController extends Controller
{
    public function index(Request $request)
    {
        $rmaReasons = RmaReasonRepo::list($request->only('name'));

        $data = [
            'languages'  => LanguageRepo::all(),
            'rmaReasons' => RmaReasonDetail::collection($rmaReasons)->jsonSerialize(),
        ];

        $data = hook_filter('admin.rma_reason.index.data', $data);

        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.rma_reasons.index', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $rmaReason = RmaReasonRepo::create($request->only('name'));

        return json_success(trans('common.created_success'), $rmaReason);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $rmaReason = RmaReasonRepo::update($id, $request->only('name'));

        return json_success(trans('common.updated_success'), $rmaReason);
    }

    public function destroy(int $id): JsonResponse
    {
        RmaReasonRepo::delete($id);

        return json_success(trans('common.deleted_success'));
    }
}
