<?php
/**
 * PermissionRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-01 20:49:45
 * @modified   2022-08-01 20:49:45
 */

namespace Beike\Admin\Repositories;

use Beike\Models\AdminUser;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Role;

class PermissionRepo
{
    private ?AdminUser $adminUser = null;

    private ?Role $adminRole = null;

    public function setUser($user): self
    {
        $this->adminUser = $user;

        return $this;
    }

    public function setRole($role): self
    {
        $this->adminRole = $role;

        return $this;
    }

    /**
     * 所有权限列表
     *
     * @return array
     * @throws \Exception
     */
    public function getRoleCorePermissions(): array
    {
        $corePermissions = [
            ['title' => trans('admin/common.order'), 'permissions' => $this->getOrderPermissions()],
            ['title' => trans('admin/common.rma'), 'permissions' => $this->getRmaPermissions()],
            ['title' => trans('admin/common.rma_reason'), 'permissions' => $this->getRmaReasonPermissions()],
            ['title' => trans('admin/common.product'), 'permissions' => $this->getProductPermissions()],
            ['title' => trans('admin/common.category'), 'permissions' => $this->getCategoryPermissions()],
            ['title' => trans('admin/common.brand'), 'permissions' => $this->getBrandPermissions()],
            ['title' => trans('admin/common.attribute'), 'permissions' => $this->getAttributePermissions()],
            ['title' => trans('admin/common.attribute_group'), 'permissions' => $this->getAttributeGroupPermissions()],
            ['title' => trans('admin/common.customer'), 'permissions' => $this->getCustomerPermissions()],
            ['title' => trans('admin/common.customer_group'), 'permissions' => $this->getCustomerGroupPermissions()],
            ['title' => trans('admin/common.page'), 'permissions' => $this->getPagePermissions()],
            ['title' => trans('admin/common.page_category'), 'permissions' => $this->getPageCategoryPermissions()],
            ['title' => trans('admin/common.setting'), 'permissions' => $this->getSettingPermissions()],

            ['title' => trans('admin/common.plugin'), 'permissions' => $this->getPluginPermissions()],
            ['title' => trans('admin/common.marketing'), 'permissions' => $this->getMarketingPermissions()],
            ['title' => trans('admin/common.report'), 'permissions' => $this->getReportPermissions()],
            ['title' => trans('admin/common.admin_user'), 'permissions' => $this->getAdminUserPermissions()],
            ['title' => trans('admin/common.admin_role'), 'permissions' => $this->getAdminRolePermissions()],
            ['title' => trans('admin/common.region'), 'permissions' => $this->getRegionPermissions()],
            ['title' => trans('admin/common.tax_rate'), 'permissions' => $this->getTaxRatePermissions()],
            ['title' => trans('admin/common.tax_class'), 'permissions' => $this->getTaxClassPermissions()],
            ['title' => trans('admin/common.currency'), 'permissions' => $this->getCurrencyPermissions()],
            ['title' => trans('admin/common.language'), 'permissions' => $this->getLanguagePermissions()],
            ['title' => trans('admin/common.file_manager'), 'permissions' => $this->getFileManagerPermissions()],
            ['title' => trans('admin/common.zone'), 'permissions' => $this->getZonePermissions()],
            ['title' => trans('admin/common.country'), 'permissions' => $this->getCountryPermissions()],
        ];

        $corePermissions   = hook_filter('role.permissions.all', $corePermissions);

        return $corePermissions;
    }

    /**
     * 插件权限
     *
     * @return array
     * @throws \Exception
     */
    public function getRolePluginPermissions(): array
    {
        $pluginPermissions = hook_filter('role.permissions.plugin', []);

        $pluginPermissions = $this->handlePluginPermission($pluginPermissions);

        return $pluginPermissions;
    }

    /**
     * 订单权限列表
     *
     * @return array
     */
    private function getOrderPermissions(): array
    {
        $routes = ['orders_index', 'orders_export', 'orders_show', 'orders_update_status', 'orders_delete', 'orders_trashed', 'orders_restore'];
        $items  = $this->getPermissionList('order', $routes);

        return hook_filter('role.order_permissions', $items);
    }

    /**
     * 售后（退换货）权限列表
     *
     * @return array
     */
    private function getRmaPermissions(): array
    {
        $routes = ['rmas_index', 'rmas_show', 'rmas_update', 'rmas_delete'];
        $items  = $this->getPermissionList('rma', $routes);

        return hook_filter('role.rma_permissions', $items);
    }

    /**
     * 售后（退换货）原因权限列表
     *
     * @return array
     */
    private function getRmaReasonPermissions(): array
    {
        $routes = ['rma_reasons_index', 'rma_reasons_create', 'rma_reasons_update', 'rma_reasons_delete'];
        $items  = $this->getPermissionList('rma_reason', $routes);

        return hook_filter('role.rma_reason_permissions', $items);
    }

    /**
     * 商品权限列表
     *
     * @return array
     */
    private function getProductPermissions(): array
    {
        $routes = ['products_index', 'products_create', 'products_show', 'products_update', 'products_delete', 'products_trashed', 'products_restore', 'products_filter_index', 'products_filter_update'];
        $items  = $this->getPermissionList('product', $routes);

        return hook_filter('role.product_permissions', $items);
    }

    /**
     * 分类权限列表
     *
     * @return array
     */
    private function getCategoryPermissions(): array
    {
        $routes = ['categories_index', 'categories_create', 'categories_show', 'categories_update', 'categories_delete'];
        $items  = $this->getPermissionList('category', $routes);

        return hook_filter('role.category_permissions', $items);
    }

    /**
     * 品牌权限列表
     *
     * @return array
     */
    private function getBrandPermissions(): array
    {
        $routes = ['brands_index', 'brands_create', 'brands_show', 'brands_update', 'brands_delete'];
        $items  = $this->getPermissionList('brand', $routes);

        return hook_filter('role.brand_permissions', $items);
    }

    /**
     * 属性权限列表
     *
     * @return array
     */
    private function getAttributePermissions(): array
    {
        $routes = ['attributes_index', 'attributes_create', 'attributes_show', 'attributes_update', 'attributes_delete'];
        $items  = $this->getPermissionList('attribute', $routes);

        return hook_filter('role.attribute_permissions', $items);
    }

    /**
     * 属性组权限列表
     *
     * @return array
     */
    private function getAttributeGroupPermissions(): array
    {
        $routes = ['attribute_groups_index', 'attribute_groups_create', 'attribute_groups_update', 'attribute_groups_delete'];
        $items  = $this->getPermissionList('attribute_group', $routes);

        return hook_filter('role.attribute_group_permissions', $items);
    }

    /**
     * 客户权限列表
     *
     * @return array
     */
    private function getCustomerPermissions(): array
    {
        $routes = ['customers_index', 'customers_create', 'customers_show', 'customers_update', 'customers_delete'];
        $items  = $this->getPermissionList('customer', $routes);

        return hook_filter('role.customer_permissions', $items);
    }

    /**
     * 客户组权限列表
     *
     * @return array
     */
    private function getCustomerGroupPermissions(): array
    {
        $routes = ['customer_groups_index', 'customer_groups_create', 'customer_groups_show', 'customer_groups_update', 'customer_groups_delete'];
        $items  = $this->getPermissionList('customer_group', $routes);

        return hook_filter('role.customer_group_permissions', $items);
    }

    /**
     * 设置权限列表
     *
     * @return array
     */
    private function getSettingPermissions(): array
    {
        $routes = ['settings_index', 'settings_update', 'design_index', 'design_footer_index', 'design_menu_index'];
        $items  = $this->getPermissionList('setting', $routes);

        return hook_filter('role.setting_permissions', $items);
    }

    /**
     * 文章管理列表
     * @return array
     */
    private function getPagePermissions(): array
    {
        $routes = ['pages_index', 'pages_create', 'pages_show', 'pages_update', 'pages_delete'];
        $items  = $this->getPermissionList('page', $routes);

        return hook_filter('role.page_permissions', $items);
    }

    /**
     * 文章分类管理列表
     * @return array
     */
    private function getPageCategoryPermissions(): array
    {
        $routes = ['page_categories_index', 'page_categories_create', 'page_categories_show', 'page_categories_update', 'page_categories_delete'];
        $items  = $this->getPermissionList('page_category', $routes);

        return hook_filter('role.page_category_permissions', $items);
    }

    /**
     * 插件权限列表
     *
     * @return array
     */
    private function getPluginPermissions(): array
    {
        $routes = ['plugins_index', 'plugins_import', 'plugins_update', 'plugins_show', 'plugins_install', 'plugins_update_status', 'plugins_uninstall'];
        $items  = $this->getPermissionList('plugin', $routes);

        return hook_filter('role.plugin_permissions', $items);
    }

    /**
     * 插件权限列表
     *
     * @return array
     */
    private function getMarketingPermissions(): array
    {
        $routes = ['marketing_index', 'marketing_show', 'marketing_buy', 'marketing_download'];
        $items  = $this->getPermissionList('marketing', $routes);

        return hook_filter('role.marketing_permissions', $items);
    }

    /**
     * 报表权限列表
     *
     * @return array
     */
    private function getReportPermissions(): array
    {
        $routes = ['reports_sale', 'reports_view'];
        $items  = $this->getPermissionList('report', $routes);

        return hook_filter('role.report_permissions', $items);
    }

    /**
     * 后台管理员权限列表
     *
     * @return array
     */
    private function getAdminUserPermissions(): array
    {
        $routes = ['admin_users_index', 'admin_users_create', 'admin_users_show', 'admin_users_update', 'admin_users_delete'];
        $items  = $this->getPermissionList('user', $routes);

        return hook_filter('role.user_permissions', $items);
    }

    /**
     * 后台管理员权限列表
     *
     * @return array
     */
    private function getAdminRolePermissions(): array
    {
        $routes = ['admin_roles_index', 'admin_roles_create', 'admin_roles_show', 'admin_roles_update', 'admin_roles_delete'];
        $items  = $this->getPermissionList('role', $routes);

        return hook_filter('role.role_permissions', $items);
    }

    /**
     * 区域分组权限列表
     *
     * @return array
     */
    private function getRegionPermissions(): array
    {
        $routes = ['regions_index', 'regions_create', 'regions_show', 'regions_update', 'regions_delete'];
        $items  = $this->getPermissionList('region', $routes);

        return hook_filter('role.region_permissions', $items);
    }

    /**
     * 获取税率权限列表
     *
     * @return array[]
     */
    private function getTaxRatePermissions(): array
    {
        $routes = ['tax_rates_index', 'tax_rates_create', 'tax_rates_show', 'tax_rates_update', 'tax_rates_delete'];
        $items  = $this->getPermissionList('tax_rate', $routes);

        return hook_filter('role.tax_rate_permissions', $items);
    }

    /**
     * 获取税类权限列表
     *
     * @return array[]
     */
    private function getTaxClassPermissions(): array
    {
        $routes = ['tax_classes_index', 'tax_classes_create', 'tax_classes_show', 'tax_classes_update', 'tax_classes_delete'];
        $items  = $this->getPermissionList('tax_class', $routes);

        return hook_filter('role.tax_class_permissions', $items);
    }

    /**
     * 获取汇率权限列表
     *
     * @return array[]
     */
    private function getCurrencyPermissions(): array
    {
        $routes = ['currencies_index', 'currencies_create', 'currencies_show', 'currencies_update', 'currencies_delete'];
        $items  = $this->getPermissionList('currency', $routes);

        return hook_filter('role.currency_permissions', $items);
    }

    /**
     * 获取语言权限列表
     *
     * @return array[]
     */
    private function getLanguagePermissions(): array
    {
        $routes = ['languages_index', 'languages_create', 'languages_update', 'languages_delete'];
        $items  = $this->getPermissionList('language', $routes);

        return hook_filter('role.language_permissions', $items);
    }

    /**
     * 获取文件管理器权限列表
     *
     * @return array[]
     */
    private function getFileManagerPermissions(): array
    {
        $routes = ['file_manager_create', 'file_manager_show', 'file_manager_update', 'file_manager_delete'];
        $items  = $this->getPermissionList('file_manager', $routes);

        return hook_filter('role.file_manager_permissions', $items);
    }

    /**
     * 获取省份权限列表
     *
     * @return array[]
     */
    private function getZonePermissions(): array
    {
        $routes = ['zones_create', 'zones_index', 'zones_update', 'zones_delete'];
        $items  = $this->getPermissionList('zone', $routes);

        return hook_filter('role.zone_permissions', $items);
    }

    /**
     * 获取国家权限列表
     *
     * @return array[]
     */
    private function getCountryPermissions(): array
    {
        $routes = ['countries_create', 'countries_index', 'countries_update', 'countries_delete'];
        $items  = $this->getPermissionList('country', $routes);

        return hook_filter('role.country_permissions', $items);
    }

    /**
     * 处理第三方插件权限
     *
     * @param $pluginPermissions
     * @return array
     * @throws \Exception
     */
    private function handlePluginPermission($pluginPermissions): array
    {
        if (empty($pluginPermissions)) {
            return [];
        }

        foreach ($pluginPermissions as $index => $pluginPermission) {
            $itemPermissions = $pluginPermission['permissions'] ?? [];
            if (empty($itemPermissions)) {
                throw new \Exception('Empty plugin permission!');
            }
            foreach ($itemPermissions as $ipIndex => $itemPermission) {
                $code = $itemPermission['code'] ?? '';
                if (empty($code)) {
                    throw new \Exception('Empty plugin permission code!');
                }
                $pluginPermissions[$index]['permissions'][$ipIndex]['selected'] = $this->hasPermission($code);
            }
        }

        return $pluginPermissions;
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
            $items[] = ['code' => $route, 'name' => trans("admin/{$module}.{$route}"), 'selected' => $this->hasPermission($route)];
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
