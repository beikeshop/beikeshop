<?php
/**
 * VerifyCodeRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-07 15:22:05
 * @modified   2022-07-07 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\VerifyCode;

class VerifyCodeRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $verifyCode = VerifyCode::query()->create($data);

        return $verifyCode;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return VerifyCode::query()->find($id);
    }

    public static function findByAccount($account)
    {
        return VerifyCode::query()->where('account', $account)->first();
    }

    public static function deleteByAccount($account)
    {
        return VerifyCode::query()->where('account', $account)->delete();
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $verifyCode = VerifyCode::query()->find($id);
        if ($verifyCode) {
            $verifyCode->delete();
        }
    }
}
