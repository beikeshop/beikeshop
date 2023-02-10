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
        $this->adminName           = admin_name();
        $this->routeNameWithPrefix = request()->route()->getName();
        $this->adminUser           = current_user();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $adminName           = $this->adminName;
        $routeNameWithPrefix = request()->route()->getName();
        $routeName           = str_replace($adminName . '.', '', $routeNameWithPrefix);

        if (Str::startsWith($routeName, $this->getHomeSubPrefix())) {
            $routes = $this->getHomeSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        } elseif (Str::startsWith($routeName, $this->getProductSubPrefix())) {
            $routes = $this->getProductSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        } elseif (Str::startsWith($routeName, $this->getCustomerSubPrefix())) {
            $routes = $this->getCustomerSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        } elseif (Str::startsWith($routeName, $this->getOrderSubPrefix())) {
            $routes = $this->getOrderSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        } elseif (Str::startsWith($routeName, $this->getPageSubPrefix())) {
            $routes = $this->getPageSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        } elseif (Str::startsWith($routeName, $this->getSettingSubPrefix())) {
            $routes = $this->getSettingSubRoutes();
            foreach ($routes as $route) {
                $this->addLink($route, $this->equalRoute($route['route']), (bool) ($route['blank'] ?? false), $route['hide_mobile'] ?? 0);
            }
        }

        return view('admin::components.sidebar');
    }

    /**
     * 添加左侧菜单链接
     *
     * @param $routeData
     * @param $active
     * @param false $newWindow
     * @param int   $hide_mobile
     */
    private function addLink($routeData, $active, bool $newWindow = false, int $hide_mobile = 0)
    {
        $route = $routeData['route'];
        $icon  = $routeData['icon']  ?? '';
        $title = $routeData['title'] ?? '';

        $permissionRoute = str_replace('.', '_', $route);
        if ($this->adminUser->cannot($permissionRoute)) {
            return;
        }

        if (empty($title)) {
            $title = trans("admin/common.{$permissionRoute}");
        }
        $url           = admin_route($route);
        $this->links[] = [
            'title'       => $title,
            'url'         => $url,
            'icon'        => $icon,
            'active'      => $active,
            'hide_mobile' => $hide_mobile,
            'new_window'  => $newWindow,
        ];
    }

    /**
     * 获取后台首页子页面路由前缀列表
     */
    private function getHomeSubPrefix()
    {
        $prefix = ['home.'];

        return hook_filter('admin.sidebar.home.prefix', $prefix);
    }

    /**
     * 获取后台产品子页面路由前缀列表
     */
    private function getProductSubPrefix()
    {
        $prefix = ['products.', 'categories.', 'brands.', 'attribute_groups.', 'attributes.'];

        return hook_filter('admin.sidebar.product.prefix', $prefix);
    }

    /**
     * 获取后台客户子页面路由前缀列表
     */
    private function getCustomerSubPrefix()
    {
        $prefix = ['customers.', 'customer_groups.'];

        return hook_filter('admin.sidebar.customer.prefix', $prefix);
    }

    /**
     * 获取后台订单子页面路由前缀列表
     */
    private function getOrderSubPrefix()
    {
        $prefix = ['orders.', 'rmas.', 'rma_reasons.'];

        return hook_filter('admin.sidebar.order.prefix', $prefix);
    }

    /**
     * 获取后台内容子页面路由前缀列表
     */
    private function getPageSubPrefix()
    {
        $prefix = ['pages.', 'page_categories.'];

        return hook_filter('admin.sidebar.page.prefix', $prefix);
    }

    /**
     * 获取后台系统设置子页面路由前缀列表
     */
    private function getSettingSubPrefix()
    {
        $prefix = ['settings.', 'admin_users.', 'admin_roles.', 'plugins.', 'marketing.', 'tax_classes', 'tax_rates', 'regions', 'currencies', 'languages', 'design_menu', 'countries', 'zones'];

        return hook_filter('admin.sidebar.setting.prefix', $prefix);
    }

    /**
     * 获取首页子页面路由
     */
    public function getHomeSubRoutes()
    {
        $routes = [
            ['route' => 'design.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => 1, 'hide_mobile' => 1],
            ['route' => 'design_footer.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => 1, 'hide_mobile' => 1],
            ['route' => 'design_menu.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'languages.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'currencies.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'plugins.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
        ];

        return hook_filter('admin.sidebar.home_routes', $routes);
    }

    /**
     * 获取商品子页面路由
     */
    public function getProductSubRoutes()
    {
        $routes = [
            ['route' => 'categories.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'products.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'brands.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'attribute_groups.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'attributes.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'products.trashed', 'icon' => 'fa fa-tachometer-alt'],
        ];

        return hook_filter('admin.sidebar.product_routes', $routes);
    }

    /**
     * 获取商品子页面路由
     */
    public function getCustomerSubRoutes()
    {
        $routes = [
            ['route' => 'customers.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'customer_groups.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'customers.trashed', 'icon' => 'fa fa-tachometer-alt'],
        ];

        return hook_filter('admin.sidebar.customer_routes', $routes);
    }

    /**
     * 获取订单子页面路由
     */
    public function getOrderSubRoutes()
    {
        $routes = [
            ['route' => 'orders.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'rmas.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'rma_reasons.index', 'icon' => 'fa fa-tachometer-alt'],
        ];

        return hook_filter('admin.sidebar.order_routes', $routes);
    }

    /**
     * 获取文章管理子页面路由
     * @return mixed
     */
    public function getPageSubRoutes()
    {
        $routes = [
            ['route' => 'page_categories.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'pages.index', 'icon' => 'fa fa-tachometer-alt'],
        ];

        return hook_filter('admin.sidebar.pages_routes', $routes);
    }

    /**
     * 获取系统设置子页面路由
     * @return mixed
     */
    public function getSettingSubRoutes()
    {
        $routes = [
            ['route' => 'settings.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'admin_users.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'plugins.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'marketing.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
            ['route' => 'regions.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'tax_rates.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'tax_classes.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'currencies.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'languages.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'countries.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'zones.index', 'icon' => 'fa fa-tachometer-alt'],
            ['route' => 'design.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => true, 'hide_mobile' => 1],
            ['route' => 'design_footer.index', 'icon' => 'fa fa-tachometer-alt', 'blank' => true, 'hide_mobile' => 1],
            ['route' => 'design_menu.index', 'icon' => 'fa fa-tachometer-alt', 'hide_mobile' => 1],
        ];

        return hook_filter('admin.sidebar.setting_routes', $routes);
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
