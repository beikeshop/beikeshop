<?php
/**
 * AccountController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-23 20:22:54
 * @modified   2022-06-23 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\ForgottenRequest;
use http\Env\Request;
use Illuminate\Support\Facades\Hash;
use function auth;
use function view;

class AccountController extends Controller
{
    /**
     * 个人中心首页
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = auth(Customer::AUTH_GUARD)->user()->toArray();
        $data['avatar'] = image_resize($data['avatar']);
        return view('account/account', $data);
    }

    /**
     * 修改密码，提交"origin_password"、"password", "password_confirmation", 验证新密码和确认密码相等，且原密码正确则修改密码
     * @param ForgottenRequest $request
     * @return array
     * @throws \Exception
     */
    public function updatePassword(ForgottenRequest $request)
    {
        if (Hash::make($request->get('origin_password')) != current_customer()->getAuthPassword()) {
            throw new \Exception("原密码错误");
        }
        CustomerRepo::update(current_customer(), ['password' => $request->get('password')]);

        return json_success('密码修改成功');
    }
}
