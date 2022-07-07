<?php
/**
 * ForgottenController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-06 15:39:08
 * @modified   2022-07-06 15:39:08
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Shop\Services\AccountService;
use Illuminate\Http\Request;

class ForgottenController
{
    /**
     * 找回密码页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('account/forgotten');
    }

    /**
     * 接收email地址，生成验证码发送到邮件地址
     * @param Request $request
     * @return array
     */
    public function sendVerifyCode(Request $request)
    {
        AccountService::sendVerifyCodeForForgotten($request->get('email'), 'email');
        return json_success('验证码已发送，请查看并输入验证码');
    }

    /**
     * 接收验证码和新密码、确认密码，验证验证码是否正确、密码和确认密码是否相等，然后修改密码
     * @param Request $request
     * @return array
     */
    public function changePassword(ForgottenRequest $request)
    {
        AccountService::verifyAndChangePassword($request->get('code'), $request->get('account'), $request->get('password'));

        return json_success('密码已修改');
    }
}
