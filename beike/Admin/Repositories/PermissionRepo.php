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
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Role;

class PermissionRepo
{
    private AdminUser $adminUser;
    private Role $adminRole;

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
            ['code' => 'products_index', 'name' => '商品列表', 'selected' => $this->hasPermission('products_index')],
            ['code' => 'products_create', 'name' => '商品创建', 'selected' => $this->hasPermission('products_create')],
            ['code' => 'products_show', 'name' => '商品详情', 'selected' => $this->hasPermission('products_show')],
            ['code' => 'products_update', 'name' => '商品编辑', 'selected' => $this->hasPermission('products_update')],
            ['code' => 'products_delete', 'name' => '商品删除', 'selected' => $this->hasPermission('products_delete')],
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
            ['code' => 'orders_index', 'name' => '订单列表', 'selected' => $this->hasPermission('orders_index')],
            ['code' => 'orders_create', 'name' => '订单创建', 'selected' => $this->hasPermission('orders_create')],
            ['code' => 'orders_show', 'name' => '订单详情', 'selected' => $this->hasPermission('orders_show')],
            ['code' => 'orders_update', 'name' => '订单编辑', 'selected' => $this->hasPermission('orders_update')],
            ['code' => 'orders_delete', 'name' => '订单删除', 'selected' => $this->hasPermission('orders_delete')],
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
        try {
            if ($this->adminRole) {
                return $this->adminRole->hasPermissionTo($permission);
            } elseif ($this->adminUser) {
                return $this->adminUser->can($permission);
            }
        } catch (PermissionDoesNotExist $exception) {
            return false;
        }
        return false;
    }
}
