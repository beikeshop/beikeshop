<?php
/**
 * LoginController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-22 20:22:54
 * @modified   2022-06-22 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (current_customer()) {
            return redirect(shop_route('account.index'));
        }
        return view('account/login');
    }

    public function store(LoginRequest $request)
    {
        if (!auth(Customer::AUTH_GUARD)->attempt($request->only('email', 'password'))) {
            throw new \Exception(trans('shop/login.email_or_password_error'));
        }

        $customer = current_customer();
        if ($customer && $customer->status != 1) {
            Auth::guard(Customer::AUTH_GUARD)->logout();
            throw new \Exception(trans('shop/login.customer_inactive'));
        }
        return json_success(trans('shop/login.login_successfully'));
    }
}
