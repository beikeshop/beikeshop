<?php
/**
 * CheckoutController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-07-04 14:55:12
 * @modified   2023-07-04 14:55:12
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Shop\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            $data = (new CheckoutService)->checkoutData();

            return hook_filter('checkout.index.data', $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 更改结算信息
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request): mixed
    {
        try {
            $requestData = $request->all();

            $data = (new CheckoutService)->update($requestData);

            return hook_filter('checkout.update.data', $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 确认提交订单
     *
     * @return mixed
     * @throws \Throwable
     */
    public function confirm()
    {
        try {
            $data = (new CheckoutService)->confirm();

            return hook_filter('checkout.confirm.data', $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
