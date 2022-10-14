<?php
/**
 * CartService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-01-05 10:12:57
 * @modified   2022-01-05 10:12:57
 */

namespace Beike\Shop\Services;


use Beike\Libraries\Notification;
use Beike\Models\Customer;
use Beike\Repositories\CustomerRepo;
use Beike\Repositories\VerifyCodeRepo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountService
{
    /**
     * 注册用户
     *
     * @param array $data // ['email', 'password']
     * @return mixed
     */
    public static function register(array $data)
    {
        $data['customer_group_id'] = system_setting('base.default_customer_group_id', 0); // default_customer_group_id为默认客户组名称
        $data['status'] = !system_setting('base.approve_customer'); // approve_customer为是否需要审核客户
        $data['from'] = $data['from'] ?? 'pc';
        $data['locale'] = locale();

        if ($data['email'] ?? '') {
            $data['name'] = substr($data['email'], 0, strrpos($data['email'], '@'));;
        }
        $data['avatar'] = $data['avatar'] ?? '';

        return CustomerRepo::create($data);
    }

    /**
     * 发送验证码通过$type方式，type为email或telephone
     * @param $email
     * @param $type
     * @return void
     */
    public static function sendVerifyCodeForForgotten($email, $type)
    {
        $code = str_pad(mt_rand(10, 999999), 6, '0', STR_PAD_LEFT);

        VerifyCodeRepo::deleteByAccount($email);
        VerifyCodeRepo::create([
            'account' => $email,
            'code' => $code,
        ]);

        Log::info("找回密码验证码：{$code}");

        Notification::verifyCode($code, "您的验证码是%s,该验证码仅用于找回密码。", $type);
    }

    /**
     * 验证验证码是否正确，并修改密码为新密码
     * @param $code
     * @param $account
     * @param $password
     * @param $type $account类型，email代表$account为邮箱地址，telephone代表$account为手机号码
     * @return void
     */
    public static function verifyAndChangePassword($code, $account, $password, $type = 'email')
    {
        $verifyCode = VerifyCodeRepo::findByAccount($account);
        if ($verifyCode->created_at->addMinutes(10) < Carbon::now()) {
            $verifyCode->delete();
            throw new \Exception(trans('shop/account.verify_code_expired'));
        }

        if ($verifyCode->code != $code) {
            throw new \Exception(trans('shop/account.verify_code_error'));
        }

        if ($type == 'email') {
            $customer = CustomerRepo::findByEmail($account);
            if (!$customer) {
                throw new \Exception(trans('shop/account.account_not_exist'));
            }
        } elseif ($type == 'telephone') {
            throw new \Exception("暂不支持手机号码找回密码");
        } else {
            throw new \Exception("找回密码类型错误");
        }
        CustomerRepo::update($customer, ['password' => $password]);
        $verifyCode->delete();
    }
}
