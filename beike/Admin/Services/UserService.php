<?php
/**
 * UserService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-14 12:12:57
 * @modified   2022-07-14 12:12:57
 */

namespace Beike\Admin\Services;

use Beike\Repositories\UserRepo;
use Beike\Repositories\VerifyCodeRepo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * 发送验证码通过$type方式，type为email或telephone
     * @param $email
     * @return void
     */
    public static function sendVerifyCodeForForgotten($email)
    {
        $code = str_pad(mt_rand(10, 999999), 6, '0', STR_PAD_LEFT);

        VerifyCodeRepo::deleteByAccount($email);
        VerifyCodeRepo::create([
            'account' => $email,
            'code'    => $code,
        ]);

        Log::info("找回密码验证码：{$code}");

        UserRepo::findByEmail($email)->notifyVerifyCodeForForgotten($code);
    }

    /**
     * 验证验证码是否正确，并修改密码为新密码
     * @param $code
     * @param $account
     * @param $password
     * @return void
     */
    public static function verifyAndChangePassword($code, $account, $password)
    {
        $verifyCode = VerifyCodeRepo::findByAccount($account);
        if ($verifyCode->created_at->addMinutes(10) < Carbon::now()) {
            $verifyCode->delete();

            throw new \Exception(trans('admin/user.verify_code_expired'));
        }

        if ($verifyCode->code != $code) {
            throw new \Exception(trans('admin/user.verify_code_error'));
        }

        $user = UserRepo::findByEmail($account);
        if (! $user) {
            throw new \Exception(trans('admin/user.account_not_exist'));
        }

        UserRepo::update($user, ['password' => $password]);
        $verifyCode->delete();
    }
}
