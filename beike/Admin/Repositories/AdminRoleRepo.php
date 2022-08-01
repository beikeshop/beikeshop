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

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleRepo
{
    /**
     * 创建新角色
     *
     * @param $data
     * @return Role
     * @throws \Exception
     */
    public static function createAdminRole($data): Role
    {
        $adminRole = new Role([
            'name' => $data['name'],
            'guard_name' => 'web_admin',
        ]);
        $adminRole->save();

        $permissions = $data['permissions'];
        self::syncPermissions($adminRole, $permissions);
        return $adminRole;
    }


    /**
     * 编辑新角色
     *
     * @param $data
     * @return Role
     * @throws \Exception
     */
    public static function updateAdminRole($data): Role
    {
        $adminRole = Role::findById($data['id']);
        $adminRole->update([
            'name' => $data['name'],
            'guard_name' => 'web_admin',
        ]);

        $permissions = $data['permissions'];
        self::syncPermissions($adminRole, $permissions);
        return $adminRole;
    }


    /**
     * 同步所有权限
     *
     * @param $adminRole
     * @param $permissions
     * @throws \Exception
     */
    private static function syncPermissions($adminRole, $permissions)
    {
        $items = [];
        foreach ($permissions as $groupedPermissions) {
            foreach ($groupedPermissions['permissions'] as $groupedPermission) {
                if ($groupedPermission['selected']) {
                    $code = $groupedPermission['code'];
                    Permission::findOrCreate($code);
                    $items[] = $code;
                }
            }
        }
        if (empty($items)) {
            throw new \Exception('无效的权限');
        }
        $adminRole->syncPermissions($items);
    }


    /**
     * 删除角色
     *
     * @param $adminRoleId
     */
    public static function deleteAdminRole($adminRoleId)
    {
        $adminRole = Role::query()->find($adminRoleId);
        $adminRole->delete();
    }
}
