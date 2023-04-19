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
    public static function getTokenByAdminUser($adminUser)
    {
        $adminUserId = self::getAdminUserId($adminUser);
        if (empty($adminUserId)) {
            return null;
        }
        return AdminUserToken::query()->where('admin_user_id', $adminUserId)->get();
    }


    public static function updateTokensByUser($adminUser, $tokens)
    {
        $adminUserId = self::getAdminUserId($adminUser);
        if (empty($adminUserId)) {
            return null;
        }

        AdminUserToken::query()->where('admin_user_id', $adminUserId)->delete();
        if (empty($tokens)) {
            return null;
        }

        foreach ($tokens as $token) {
            AdminUserToken::query()->create([
                'admin_user_id' => $adminUserId,
                'token' => $token
            ]);
        }
    }


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
