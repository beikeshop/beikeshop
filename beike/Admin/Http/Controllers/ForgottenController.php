<?php
/**
 * ForgottenController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-14 11:39:08
 * @modified   2022-07-14 11:39:08
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\ForgottenRequest;
use Beike\Admin\Services\UserService;
use Illuminate\Http\Request;

class ForgottenController
{
    /**
     * 找回密码页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin::pages.user.forgotten');
    }

    /**
     * 接收email地址，生成验证码发送到邮件地址
     * @param Request $request
     * @return array
     */
    public function sendVerifyCode(Request $request)
    {
        UserService::sendVerifyCodeForForgotten($request->get('email'));
        return json_success('验证码已发送，请查看并输入验证码');
    }

    /**
     * 接收验证码和新密码、确认密码，验证验证码是否正确、密码和确认密码是否相等，然后修改密码
     * @param Request $request
     * @return array
     */
    public function changePassword(ForgottenRequest $request)
    {
        UserService::verifyAndChangePassword($request->get('code'), $request->get('email'), $request->get('password'));

        return json_success(trans('common.updated_success'));
    }
}
