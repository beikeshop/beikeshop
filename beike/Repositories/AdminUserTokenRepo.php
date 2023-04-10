<?php
/**
 * AdminUserTokenRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 10:21:25
 * @modified   2023-04-20 10:21:25
 */

namespace Beike\Repositories;

use Beike\Models\AdminUser;
use Beike\Models\AdminUserToken;

class AdminUserTokenRepo
{
    /**
     * @param $adminUser
     * @return mixed
     */
    public static function getTokenByAdminUser($adminUser)
    {
        $adminUserId = self::getAdminUserId($adminUser);
        if (empty($adminUserId)) {
            return null;
        }

        return AdminUserToken::query()->where('admin_user_id', $adminUserId)->get();
    }

    /**
     * @param $token
     * @return mixed
     */
    public static function getAdminUserTokenByToken($token)
    {
        return AdminUserToken::query()->where('token', $token)->first();
    }

    /**
     * @param $adminUser
     * @param $tokens
     * @return void
     */
    public static function updateTokensByUser($adminUser, $tokens)
    {
        $adminUserId = self::getAdminUserId($adminUser);
        if (empty($adminUserId)) {
            return;
        }

        AdminUserToken::query()->where('admin_user_id', $adminUserId)->delete();
        if (empty($tokens)) {
            return;
        }

        foreach ($tokens as $token) {
            AdminUserToken::query()->create([
                'admin_user_id' => $adminUserId,
                'token'         => $token,
            ]);
        }
    }

    /**
     * @param $adminUser
     * @return int|mixed
     */
    private static function getAdminUserId($adminUser)
    {
        $adminUserId = 0;
        if ($adminUser instanceof AdminUser) {
            $adminUserId = $adminUser->id;
        } elseif (is_int($adminUser)) {
            $adminUserId = $adminUser;
        }

        return $adminUserId;
    }
}
