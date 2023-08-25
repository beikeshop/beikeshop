<?php
/**
 * RmaController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-16 15:16:54
 * @modified   2023-08-16 15:16:54
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\Rma;
use Beike\Repositories\RmaRepo;
use Beike\Shop\Http\Requests\RmaRequest;
use Beike\Shop\Services\RmaService;
use Illuminate\Http\JsonResponse;

class RmaController extends Controller
{
    /**
     * RMA List
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $rmaList = RmaRepo::listByCustomer(current_customer());

        return json_success(trans('common.get_success'), $rmaList);
    }

    /**
     * @param Rma $rma
     * @return JsonResponse
     */
    public function show(Rma $rma): JsonResponse
    {
        return json_success(trans('common.get_success'), $rma);
    }

    /**
     * @param RmaRequest $request
     * @return JsonResponse
     */
    public function store(RmaRequest $request): JsonResponse
    {
        $rma = RmaService::createFromShop($request->only('order_product_id', 'quantity', 'opened', 'rma_reason_id', 'type', 'comment'));

        return json_success(trans('common.get_success'), $rma);
    }
}
