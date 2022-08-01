<?php
/**
 * AdminRoleRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-01 21:12:11
 * @modified   2022-08-01 21:12:11
 */

namespace Beike\Admin\Repositories;

use Spatie\Permission\Models\Role;

class AdminRoleRepo
{
    public static function createAdminRole($data): Role
    {
        $adminRole = new Role([
            'name' => $data['name'],
            'guard_name' => 'web_admin',
        ]);
        $adminRole->save();
        $adminRole->givePermissionTo($data['permissions']);
        return $adminRole;
    }


    public static function updateAdminRole($data)
    {

    }


    public static function deleteAdminRole($adminRoleId)
    {
        $adminRole = Role::query()->find($adminRoleId);
        $adminRole->delete();
    }
}
