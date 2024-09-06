<?php
/**
 * LoginController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-21 14:22:26
 * @modified   2022-12-21 14:22:26
 */

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Http\Requests\LoginRequest;
use Beike\Models\AdminUser;

class LoginController extends Controller
{
    public function show()
    {
        if (auth(AdminUser::AUTH_GUARD)->check()) {
            return redirect()->back();
        }

        return view('admin::pages.login.login', \request()->only('admin_email', 'admin_password'));
    }

    public function store(LoginRequest $loginRequest)
    {

        hook_action("admin.login.store.before",$loginRequest);
        
        if (auth(AdminUser::AUTH_GUARD)->attempt($loginRequest->validated())) {
            return redirect(admin_route('home.index'));
        }

        return redirect()->back()->with(['error' => trans('auth.failed')])->withInput();
    }
}
