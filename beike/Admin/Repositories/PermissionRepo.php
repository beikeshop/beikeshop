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
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

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
            ['title' => '订单管理', 'permissions' => $this->getOrderPermissions()],
            ['title' => '商品管理', 'permissions' => $this->getProductPermissions()],
            ['title' => '客户管理', 'permissions' => $this->getCustomerPermissions()],
            ['title' => '系统设置', 'permissions' => $this->getSettingPermissions()],
            ['title' => '插件管理', 'permissions' => $this->getPluginPermissions()],
            ['title' => '区域分组', 'permissions' => $this->getRegionPermissions()],
            ['title' => '税率设置', 'permissions' => $this->getTaxRatePermissions()],
            ['title' => '税费类别', 'permissions' => $this->getTaxClassPermissions()],
            ['title' => '货币管理', 'permissions' => $this->getCurrencyPermissions()],
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
        $routes = ['orders_index', 'orders_create', 'orders_edit', 'orders_update', 'orders_delete'];
        $items = $this->getPermissionList('order', $routes);
        return $items;
    }


    /**
     * 商品权限列表
     *
     * @return \string[][]
     */
    private function getProductPermissions(): array
    {
        $routes = ['products_index', 'products_create', 'products_edit', 'products_update', 'products_delete'];
        $items = $this->getPermissionList('product', $routes);
        return $items;
    }


    /**
     * 客户权限列表
     *
     * @return \string[][]
     */
    private function getCustomerPermissions(): array
    {
        $routes = ['customers_index', 'customers_create', 'customers_edit', 'customers_update', 'customers_delete'];
        $items = $this->getPermissionList('customer', $routes);
        return $items;
    }


    /**
     * 设置权限列表
     *
     * @return \string[][]
     */
    private function getSettingPermissions(): array
    {
        return [
            ['code' => 'settings_index', 'name' => trans('setting.settings_index'), 'selected' => $this->hasPermission('settings_index')],
            ['code' => 'design_index', 'name' => trans('setting.design_index'), 'selected' => $this->hasPermission('design_index')],
        ];
    }


    /**
     * 插件权限列表
     *
     * @return array[]
     */
    private function getPluginPermissions(): array
    {
        $routes = ['plugins_index', 'plugins_import', 'plugins_update', 'plugins_edit', 'plugins_install', 'plugins_update_status', 'plugins_uninstall'];
        $items = $this->getPermissionList('plugin', $routes);
        return $items;
    }


    /**
     * 区域分组权限列表
     *
     * @return array[]
     */
    private function getRegionPermissions(): array
    {
        $routes = ['regions_index', 'regions_create', 'regions_edit', 'regions_update', 'regions_delete'];
        $items = $this->getPermissionList('region', $routes);
        return $items;
    }


    /**
     * 获取税率权限列表
     *
     * @return array[]
     */
    private function getTaxRatePermissions(): array
    {
        $routes = ['tax_rates_index', 'tax_rates_create', 'tax_rates_edit', 'tax_rates_update', 'tax_rates_delete'];
        $items = $this->getPermissionList('tax_rate', $routes);
        return $items;
    }


    /**
     * 获取税类权限列表
     *
     * @return array[]
     */
    private function getTaxClassPermissions(): array
    {
        $routes = ['tax_classes_index', 'tax_classes_create', 'tax_classes_edit', 'tax_classes_update', 'tax_classes_delete'];
        $items = $this->getPermissionList('tax_class', $routes);
        return $items;
    }


    /**
     * 获取汇率权限列表
     *
     * @return array[]
     */
    private function getCurrencyPermissions(): array
    {
        $routes = ['currencies_index', 'currencies_create', 'currencies_edit', 'currencies_update', 'currencies_delete'];
        $items = $this->getPermissionList('currency', $routes);
        return $items;
    }


    /**
     * 根据模块和路由返回权限列表
     *
     * @param $module
     * @param $routes
     * @return array
     */
    private function getPermissionList($module, $routes): array
    {
        $items = [];
        foreach ($routes as $route) {
            $items[] = ['code' => $route, 'name' => trans("{$module}.{$route}"), 'selected' => $this->hasPermission($route)];
        }
        return $items;
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
