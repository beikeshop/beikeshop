<?php
/**
 * PermissionRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-01 20:49:45
 * @modified   2022-08-01 20:49:45
 */

namespace Beike\Admin\Repositories;

use Beike\Models\AdminUser;
use Spatie\Permission\Models\Role;

class PermissionRepo
{
    private $adminUser;
    private $adminRole;

    public function setUser(AdminUser $user): PermissionRepo
    {
        $this->adminUser = $user;
        return $this;
    }

    public function setRole(Role $role): PermissionRepo
    {
        $this->adminRole = $role;
        return $this;
    }

    /**
     * 所有权限列表
     *
     * @return \string[][][]
     */
    public function getAllPermissions(): array
    {
        $permissions = [
            [
                'title' => '产品权限',
                'permissions' => self::getProductPermissions(),
            ],
            [
                'title' => '订单权限',
                'permissions' => self::getOrderPermissions(),
            ]
        ];

        return $permissions;
    }


    /**
     * 商品权限列表
     *
     * @return \string[][]
     */
    private function getProductPermissions(): array
    {
        return [
            ['code' => 'product_list', 'name' => '商品列表', 'selected' => $this->hasPermission('product_list')],
            ['code' => 'product_create', 'name' => '商品创建', 'selected' => $this->hasPermission('product_create')],
            ['code' => 'product_show', 'name' => '商品详情', 'selected' => $this->hasPermission('product_show')],
            ['code' => 'product_update', 'name' => '商品编辑', 'selected' => $this->hasPermission('product_update')],
            ['code' => 'product_delete', 'name' => '商品删除', 'selected' => $this->hasPermission('product_delete')],
        ];
    }


    /**
     * 订单权限列表
     *
     * @return \string[][]
     */
    private function getOrderPermissions(): array
    {
        return [
            ['code' => 'order_list', 'name' => '订单列表', 'selected' => $this->hasPermission('order_list')],
            ['code' => 'order_create', 'name' => '订单创建', 'selected' => $this->hasPermission('order_create')],
            ['code' => 'order_show', 'name' => '订单详情', 'selected' => $this->hasPermission('order_show')],
            ['code' => 'order_update', 'name' => '订单编辑', 'selected' => $this->hasPermission('order_update')],
            ['code' => 'order_delete', 'name' => '订单删除', 'selected' => $this->hasPermission('order_delete')],
        ];
    }


    /**
     * 判断当前用户或者角色是否有权限
     *
     * @param $permission
     * @return bool
     */
    private function hasPermission($permission): bool
    {
        if ($this->adminRole) {
            return $this->adminRole->hasPermissionTo($permission);
        } elseif ($this->adminUser) {
            return $this->adminUser->hasPermissionTo($permission);
        }
        return false;
    }
}
