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
use Beike\Repositories\CartRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $data = [
            'request_data' => $request->all(),
        ];

        try {
            hook_action('shop.account.login.before', $data);

            $guestCartProduct       = CartRepo::allCartProducts(0);
            if (! auth(Customer::AUTH_GUARD)->attempt($request->only('email', 'password'))) {
                throw new NotAcceptableHttpException(trans('shop/login.email_or_password_error'));
            }

            $customer = current_customer();
            if (empty($customer)) {
                throw new NotFoundHttpException(trans('shop/login.empty_customer'));
            } elseif ($customer->active != 1) {
                Auth::guard(Customer::AUTH_GUARD)->logout();

                throw new NotFoundHttpException(trans('shop/login.customer_inactive'));
            } elseif ($customer->status == 'pending') {
                Auth::guard(Customer::AUTH_GUARD)->logout();

                throw new NotFoundHttpException(trans('shop/login.customer_not_approved'));
            } elseif ($customer->status == 'rejected') {
                Auth::guard(Customer::AUTH_GUARD)->logout();

                throw new NotFoundHttpException(trans('shop/login.customer_rejected'));
            }

            CartRepo::mergeGuestCart($customer, $guestCartProduct);

            hook_action('shop.account.login.after', $data);

            return json_success(trans('shop/login.login_successfully'));
        } catch (NotAcceptableHttpException $e) {
            return json_fail($e->getMessage(), ['error' => 'password']);
        } catch (\Exception $e) {
            return json_fail($e->getMessage(), ['error' => 'status']);
        }
    }
}
