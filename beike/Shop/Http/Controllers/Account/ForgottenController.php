<?php
/**
 * ForgottenController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-06 15:39:08
 * @modified   2022-07-06 15:39:08
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Shop\Http\Requests\ForgottenRequest;
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
        return json_success(trans('shop/forgotten.verification_code_sent'));
    }

    /**
     * 接收验证码和新密码、确认密码，验证验证码是否正确、密码和确认密码是否相等，然后修改密码
     * @param Request $request
     * @return array
     */
    public function changePassword(ForgottenRequest $request)
    {
        AccountService::verifyAndChangePassword($request->get('code'), $request->get('email'), $request->get('password'));

        return json_success(trans('shop/forgotten.password_updated'));
    }
}
