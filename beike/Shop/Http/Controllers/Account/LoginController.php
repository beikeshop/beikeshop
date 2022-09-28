<?php
/**
 * LoginController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-22 20:22:54
 * @modified   2022-06-22 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Beike\Shop\Http\Requests\LoginRequest;
use Beike\Shop\Http\Controllers\Controller;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginController extends Controller
{
    public function index()
    {
        if (current_customer()) {
            return redirect(shop_route('account.index'));
        }
        $loginData = [
            'social_buttons' => hook_filter('login.social.buttons', []),
        ];
        return view('account/login', $loginData);
    }

    public function store(LoginRequest $request)
    {
        if (!auth(Customer::AUTH_GUARD)->attempt($request->only('email', 'password'))) {
            throw new NotAcceptableHttpException(trans('shop/login.email_or_password_error'));
        }

        $customer = current_customer();
        if ($customer && $customer->status != 1) {
            Auth::guard(Customer::AUTH_GUARD)->logout();
            throw new NotFoundHttpException(trans('shop/login.customer_inactive'));
        }
        return json_success(trans('shop/login.login_successfully'));
    }
}
