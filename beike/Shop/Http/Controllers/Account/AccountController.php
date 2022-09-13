<?php
/**
 * AccountController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-23 20:22:54
 * @modified   2022-06-23 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\OrderRepo;
use Illuminate\Support\Facades\Hash;
use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\ForgottenRequest;
use Beike\Shop\Http\Resources\CustomerResource;
use Beike\Shop\Http\Resources\Account\OrderList;

class AccountController extends Controller
{
    /**
     * 个人中心首页
     * @return mixed
     * @throws \Exception
     */
    public function index()
    {
        $customer = current_customer();
        $data = [
            'customer' => new CustomerResource($customer),
            'latest_orders' => OrderList::collection(OrderRepo::getLatestOrders($customer, 10)),
        ];
        return view('account/account', $data);
    }

    /**
     * 修改密码，提交 "origin_password"、"password", "password_confirmation", 验证新密码和确认密码相等，且原密码正确则修改密码
     * @param ForgottenRequest $request
     * @return array
     * @throws \Exception
     */
    public function updatePassword(ForgottenRequest $request): array
    {
        if (Hash::make($request->get('origin_password')) != current_customer()->getAuthPassword()) {
            throw new \Exception(trans('shop/account.edit.origin_password_fail'));
        }
        CustomerRepo::update(current_customer(), ['password' => $request->get('password')]);

        return json_success(trans('shop/account.edit.password_edit_success'));
    }
}
