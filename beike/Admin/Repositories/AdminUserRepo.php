<?php
/**
 * AdminUserRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-01 20:30:44
 * @modified   2022-08-01 20:30:44
 */

namespace Beike\Admin\Repositories;

use Beike\Models\AdminUser;

class AdminUserRepo
{
    public static function createAdminUser($data): AdminUser
    {
        $adminUser = new AdminUser([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'active' => true,
        ]);
        $adminUser->save();

        $adminUser->assignRole($data['roles']);
        return $adminUser;
    }


    public static function updateAdminUser($data): AdminUser
    {
        $id = $data['id'] ?? 0;
        $password = $data['password'] ?? '';
        $adminUser = AdminUser::query()->find($id);
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'active' => true,
        ];
        if ($password) {
            $userData['password'] = bcrypt($password);
        }
        $adminUser->update($userData);
        $adminUser->syncRoles($data['roles']);
        return $adminUser;
    }


    public static function deleteAdminUser($adminUserId)
    {
        $adminUser = AdminUser::query()->find($adminUserId);
        $adminUser->delete();
    }
}
