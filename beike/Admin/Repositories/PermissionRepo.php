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
                'title' => '订单管理',
                'permissions' => $this->getOrderPermissions(),
            ],
            [
                'title' => '商品管理',
                'permissions' => $this->getProductPermissions(),
            ],
            [
                'title' => '客户管理',
                'permissions' => $this->getCustomerPermissions(),
            ],
            [
                'title' => '系统设置',
                'permissions' => $this->getSettingPermissions(),
            ],
        ];

        return $permissions;
    }


    /**
     * 订单权限列表
     *
     * @return \string[][]
     */
    private function getOrderPermissions(): array
    {
        return [
            ['code' => 'orders_index', 'name' => '列表', 'selected' => $this->hasPermission('orders_index')],
            ['code' => 'orders_create', 'name' => '创建', 'selected' => $this->hasPermission('orders_create')],
            ['code' => 'orders_show', 'name' => '详情', 'selected' => $this->hasPermission('orders_show')],
            ['code' => 'orders_update', 'name' => '编辑', 'selected' => $this->hasPermission('orders_update')],
            ['code' => 'orders_delete', 'name' => '删除', 'selected' => $this->hasPermission('orders_delete')],
        ];
    }


    /**
     * 商品权限列表
     *
     * @return \string[][]
     */
    private function getProductPermissions(): array
    {
        return [
            ['code' => 'products_index', 'name' => '列表', 'selected' => $this->hasPermission('products_index')],
            ['code' => 'products_create', 'name' => '创建', 'selected' => $this->hasPermission('products_create')],
            ['code' => 'products_show', 'name' => '详情', 'selected' => $this->hasPermission('products_show')],
            ['code' => 'products_update', 'name' => '编辑', 'selected' => $this->hasPermission('products_update')],
            ['code' => 'products_delete', 'name' => '删除', 'selected' => $this->hasPermission('products_delete')],
        ];
    }


    /**
     * 客户权限列表
     *
     * @return \string[][]
     */
    private function getCustomerPermissions(): array
    {
        return [
            ['code' => 'customers_index', 'name' => '列表', 'selected' => $this->hasPermission('customers_index')],
            ['code' => 'customers_create', 'name' => '创建', 'selected' => $this->hasPermission('customers_create')],
            ['code' => 'customers_show', 'name' => '详情', 'selected' => $this->hasPermission('customers_show')],
            ['code' => 'customers_update', 'name' => '编辑', 'selected' => $this->hasPermission('customers_update')],
            ['code' => 'customers_delete', 'name' => '删除', 'selected' => $this->hasPermission('customers_delete')],
        ];
    }


    /**
     * 会员权限列表
     *
     * @return \string[][]
     */
    private function getSettingPermissions(): array
    {
        return [
            ['code' => 'settings_index', 'name' => '系统设置', 'selected' => $this->hasPermission('settings_index')],
            ['code' => 'admin_users_index', 'name' => '后台用户', 'selected' => $this->hasPermission('admin_users_index')],
            ['code' => 'plugins_index', 'name' => '插件列表', 'selected' => $this->hasPermission('plugins_index')],
            ['code' => 'regions_index', 'name' => '区域分组', 'selected' => $this->hasPermission('regions_index')],
            ['code' => 'tax_rates_index', 'name' => '税费设置', 'selected' => $this->hasPermission('tax_rates_index')],
            ['code' => 'tax_classes_index', 'name' => '税费类别', 'selected' => $this->hasPermission('tax_classes_index')],
            ['code' => 'currencies_index', 'name' => '货币管理', 'selected' => $this->hasPermission('currencies_index')],
            ['code' => 'design_index', 'name' => '首页装修', 'selected' => $this->hasPermission('design_index')],
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
