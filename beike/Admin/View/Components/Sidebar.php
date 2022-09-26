<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links = [];
    private string $adminName;
    private ?string $routeNameWithPrefix;
    private ?AdminUser $adminUser;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminName = admin_name();
        $this->routeNameWithPrefix = request()->route()->getName();
        $this->adminUser = current_user();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $adminName = $this->adminName;
        $routeNameWithPrefix = request()->route()->getName();
        $routeName = str_replace($adminName . '.', '', $routeNameWithPrefix);

        if (Str::startsWith($routeName, ['home.'])) {
            $routes = $this->getHomeSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        } elseif (Str::startsWith($routeName, ['products.', 'categories.', 'brands.'])) {
            $routes = $this->getProductSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        } elseif (Str::startsWith($routeName, ['customers.', 'customer_groups.'])) {
            $routes = $this->getCustomerSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        } elseif (Str::startsWith($routeName, ['orders.', 'rmas.', 'rma_reasons.'])) {
            $routes = $this->getOrderSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        } elseif (Str::startsWith($routeName, ['pages.'])) {
            $routes = $this->getPagesSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        } elseif (Str::startsWith($routeName, ['settings.', 'admin_users.', 'admin_roles.', 'plugins.', 'marketing.', 'tax_classes', 'tax_rates', 'regions', 'currencies', 'languages', 'design_menu', 'countries', 'zones'])) {
            $routes = $this->getSettingSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route['route'], $route['icon'] ?? '', $this->equalRoute($route['route']), (bool)($route['blank'] ?? false));
            }
        }

        return view('admin::components.sidebar');
    }


    /**
     * 添加左侧菜单链接
     *
     * @param $route
     * @param $icon
     * @param $active
     * @param false $newWindow
     */
    public function addLink($route, $icon, $active, bool $newWindow = false)
    {
        $permissionRoute = str_replace('.', '_', $route);
        if ($this->adminUser->cannot($permissionRoute)) {
            return;
        }

        $title = trans("admin/common.{$permissionRoute}");
        $url = admin_route($route);
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'active' => $active,
            'new_window' => $newWindow
        ];
    }


    /**
     * 获取首页子页面路由
     */
    private function getHomeSubRoutes()
    {
        $routes = [
            ['route' => 'design.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => 1],
            ['route' => 'design_footer.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => 1],
            ['route' => 'design_menu.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'languages.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'currencies.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'plugins.index', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.home_routes', $routes);
    }


    /**
     * 获取商品子页面路由
     */
    private function getProductSubRoutes()
    {
        $routes = [
            ['route' => 'categories.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'products.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'brands.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'products.trashed', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.product_routes', $routes);
    }


    /**
     * 获取商品子页面路由
     */
    private function getCustomerSubRoutes()
    {
        $routes = [
            ['route' => 'customers.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'customer_groups.index', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.customer_routes', $routes);
    }


    /**
     * 获取订单子页面路由
     */
    private function getOrderSubRoutes()
    {
        $routes = [
            ['route' => 'orders.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'rmas.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'rma_reasons.index', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.order_routes', $routes);
    }

    /**
     * 获取内容管理子页面路由
     * @return mixed
     */
    private function getPagesSubRoutes()
    {
        $routes = [
            ['route' => 'pages.index', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.pages_routes', $routes);
    }

    /**
     * 获取系统设置子页面路由
     * @return mixed
     */
    private function getSettingSubRoutes()
    {
        $routes = [
            ['route' => 'settings.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'admin_users.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'plugins.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'marketing.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'regions.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'tax_rates.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'tax_classes.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'currencies.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'languages.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'countries.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'zones.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'design.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => true],
            ['route' => 'design_footer.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => true],
            ['route' => 'design_menu.index', 'icon' => 'fa fa-tachometer-alt'],
        ];
        return hook_filter('sidebar.setting_routes', $routes);
    }


    /**
     * 是否为当前访问路由
     *
     * @param $routeName
     * @return bool
     */
    private function equalRoute($routeName): bool
    {
        $currentRouteName = str_replace($this->adminName . '.', '', $this->routeNameWithPrefix);
        return $routeName == $currentRouteName;
    }
}
