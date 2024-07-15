<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Beike\Plugin\Plugin;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links = [];

    public ?array $currentLink;

    private string $adminName;

    private ?string $routeNameWithPrefix;

    private ?string $currentRouteName;

    private ?string $currentPrefix;

    private ?AdminUser $adminUser;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminName = admin_name();
        $this->adminUser = current_user();

        $this->routeNameWithPrefix = request()->route()->getName();
        $this->currentRouteName    = str_replace($this->adminName . '.', '', $this->routeNameWithPrefix);

        $routeData           = explode('.', $this->currentRouteName);
        $this->currentPrefix = $routeData[0] ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $this->links = $this->getMenus();
        $this->handleMenus();
        $this->currentLink = $this->getCurrentLink();

        return view('admin::components.sidebar');
    }

    /**
     * 返回所有菜单
     * 小图标地址 https://icons.getbootstrap.com/
     *
     * @return mixed
     */
    private function getMenus(): mixed
    {
        $menus = [
            [
                'route'    => 'home.index',
                'title'    => trans('admin/common.home'),
                'icon'     => 'bi bi-house',
                'prefixes' => $this->getHomeSubPrefix(),
            ],
            [
                'route'    => 'orders.index',
                'title'    => trans('admin/common.order'),
                'icon'     => 'bi bi-clipboard-check',
                'prefixes' => $this->getOrderSubPrefix(),
                'children' => $this->getOrderSubRoutes(),
            ],
            [
                'route'    => 'products.index',
                'title'    => trans('admin/common.product'),
                'icon'     => 'bi bi-box-seam',
                'prefixes' => $this->getProductSubPrefix(),
                'children' => $this->getProductSubRoutes(),
            ],
            [
                'route'    => 'customers.index',
                'title'    => trans('admin/common.customer'),
                'icon'     => 'bi bi-person-circle',
                'prefixes' => $this->getCustomerSubPrefix(),
                'children' => $this->getCustomerSubRoutes(),
            ],
            [
                'route'    => 'pages.index',
                'title'    => trans('admin/common.page'),
                'icon'     => 'bi bi-file-earmark-text',
                'prefixes' => $this->getPageSubPrefix(),
                'children' => $this->getPageSubRoutes(),
            ],
            [
                'route'    => 'reports_sale.index',
                'title'    => trans('admin/common.report'),
                'icon'     => 'bi bi-bar-chart-line',
                'prefixes' => $this->getReportSubPrefix(),
                'children' => $this->getReportSubRoutes(),
            ],
            [
                'route'    => 'theme.index',
                'title'    => trans('admin/common.design'),
                'icon'     => 'bi bi-palette',
                'prefixes' => $this->getDesignSubPrefix(),
                'children' => $this->getDesignSubRoutes(),
            ],
            [
                'route'    => 'plugins.index',
                'title'    => trans('admin/common.plugin'),
                'icon'     => 'bi bi-shop',
                'prefixes' => $this->getPluginSubPrefix(),
                'children' => $this->getPluginSubRoutes(),
            ],
            [
                'route'    => 'settings.index',
                'title'    => trans('admin/common.setting'),
                'icon'     => 'bi bi-gear',
                'prefixes' => $this->getSettingSubPrefix(),
                'children' => $this->getSettingSubRoutes(),
            ],
            [
                'route'    => 'help.index',
                'title'    => trans('admin/common.help_index'),
                'icon'     => 'bi bi-question-circle',
                'prefixes' => ['help'],
                'children' => [],
            ],
        ];

        return hook_filter('admin.components.sidebar.menus', $menus);
    }

    /**
     * 获取二级菜单
     *
     * @return array|null
     */
    private function getCurrentLink(): ?array
    {
        foreach ($this->links as $link) {
            $prefixes = $link['prefixes'] ?? [];
            if ($prefixes && in_array($this->currentPrefix, $prefixes)) {
                return $link;
            }
        }

        return null;
    }

    /**
     * 处理是否选中等数据
     */
    private function handleMenus()
    {
        foreach ($this->links as $index => $link) {
            $prefixes = $link['prefixes'] ?? [];
            if ($prefixes && in_array($this->currentPrefix, $prefixes)) {
                $this->links[$index]['active'] = true;
            } else {
                $this->links[$index]['active'] = false;
            }

            $url = $link['url'] ?? '';
            if (empty($url)) {
                $this->links[$index]['url'] = admin_route($link['route']);
            }

            $title = $link['title'] ?? '';
            if (empty($title)) {
                $permissionRoute              = str_replace('.', '_', $this->currentRouteName);
                $this->links[$index]['title'] = trans("admin/common.{$permissionRoute}");
            }

            if (! isset($link['blank'])) {
                $this->links[$index]['blank'] = false;
            }

            $icon = $link['icon'] ?? '';
            if (empty($icon)) {
                $this->links[$index]['icon'] = 'bi bi-link-45deg';
            }

            $children = $link['children'] ?? [];
            if ($children) {
                foreach ($children as $key => $item) {
                    $childPrefixes = $item['prefixes'] ?? [];
                    $excludes      = $item['excludes'] ?? [];
                    if ($prefixes && in_array($this->currentPrefix, $childPrefixes)
                                  && (! $excludes || ! in_array($this->currentRouteName, $excludes))) {
                        $this->links[$index]['children'][$key]['active'] = true;
                    } else {
                        $this->links[$index]['children'][$key]['active'] = false;
                    }

                    $url = $item['url'] ?? '';
                    if (empty($url)) {
                        $this->links[$index]['children'][$key]['url'] = admin_route($item['route']);
                    }

                    $title = $item['title'] ?? '';
                    if (empty($title)) {
                        $permissionRoute                                = str_replace('.', '_', $item['route']);
                        $this->links[$index]['children'][$key]['title'] = trans("admin/common.{$permissionRoute}");
                    }

                    if (! isset($item['blank'])) {
                        $this->links[$index]['children'][$key]['blank'] = false;
                    }
                }
            }
        }
    }

    /**
     * 获取后台首页子页面路由前缀列表
     */
    private function getHomeSubPrefix()
    {
        $prefix = ['home'];

        return hook_filter('admin.sidebar.home.prefix', $prefix);
    }

    /**
     * 获取后台产品子页面路由前缀列表
     */
    private function getProductSubPrefix()
    {
        $prefix = ['products', 'multi_filter', 'categories', 'brands', 'attribute_groups', 'attributes'];

        return hook_filter('admin.sidebar.product.prefix', $prefix);
    }

    /**
     * 获取后台客户子页面路由前缀列表
     */
    private function getCustomerSubPrefix()
    {
        $prefix = ['customers', 'customer_groups'];

        return hook_filter('admin.sidebar.customer.prefix', $prefix);
    }

    /**
     * 获取后台订单子页面路由前缀列表
     */
    private function getOrderSubPrefix()
    {
        $prefix = ['orders', 'rmas', 'rma_reasons'];

        return hook_filter('admin.sidebar.order.prefix', $prefix);
    }

    /**
     * 获取后台内容子页面路由前缀列表
     */
    private function getPageSubPrefix()
    {
        $prefix = ['pages', 'page_categories'];

        return hook_filter('admin.sidebar.page.prefix', $prefix);
    }

    /**
     * 获取后台报表子页面路由前缀列表
     */
    private function getReportSubPrefix()
    {
        $prefix = ['reports_sale', 'reports_view'];

        return hook_filter('admin.sidebar.report.prefix', $prefix);
    }

    /**
     * 获取后台设计子页面路由前缀列表
     */
    private function getDesignSubPrefix()
    {
        $prefix = ['theme', 'design_menu', 'design_app_home'];

        return hook_filter('admin.sidebar.design.prefix', $prefix);
    }

    /**
     * 获取后台设计子页面路由前缀列表
     */
    private function getPluginSubPrefix()
    {
        $prefix = ['plugins', 'marketing'];

        return hook_filter('admin.sidebar.plugin.prefix', $prefix);
    }

    /**
     * 获取后台系统设置子页面路由前缀列表
     */
    private function getSettingSubPrefix()
    {
        $prefix = [
            'settings', 'admin_users', 'admin_roles', 'tax_classes', 'tax_rates',
            'regions', 'currencies', 'languages', 'countries', 'zones', 'account',
        ];

        return hook_filter('admin.sidebar.setting.prefix', $prefix);
    }

    /**
     * 获取首页子页面路由
     */
    public function getHomeSubRoutes()
    {
        $routes = [];

        return hook_filter('admin.sidebar.home_routes', $routes);
    }

    /**
     * 获取商品子页面路由
     */
    public function getProductSubRoutes()
    {
        $routes = [
            ['route' => 'products.index', 'prefixes' => ['products'], 'excludes' => ['products.trashed']],
            ['route' => 'categories.index', 'prefixes' => ['categories']],
            ['route' => 'brands.index', 'prefixes' => ['brands']],
            ['route' => 'attribute_groups.index', 'prefixes' => ['attribute_groups']],
            ['route' => 'attributes.index', 'prefixes' => ['attributes']],
            ['route' => 'multi_filter.index', 'prefixes' => ['multi_filter']],
            ['route' => 'products.trashed', 'prefixes' => ['products'], 'excludes' => ['products.index', 'products.edit', 'products.create']],
        ];

        return hook_filter('admin.sidebar.product_routes', $routes);
    }

    /**
     * 获取商品子页面路由
     */
    public function getCustomerSubRoutes()
    {
        $routes = [
            ['route' => 'customers.index', 'prefixes' => ['customers'], 'excludes' => ['customers.trashed']],
            ['route' => 'customer_groups.index', 'prefixes' => ['customer_groups']],
            ['route' => 'customers.trashed', 'prefixes' => ['customers'], 'excludes' => ['customers.index', 'customers.edit']],
        ];

        return hook_filter('admin.sidebar.customer_routes', $routes);
    }

    /**
     * 获取订单子页面路由
     */
    public function getOrderSubRoutes()
    {
        $routes = [
            ['route' => 'orders.index', 'prefixes' => ['orders'], 'excludes' => ['orders.trashed']],
            ['route' => 'rmas.index', 'prefixes' => ['rmas']],
            ['route' => 'rma_reasons.index', 'prefixes' => ['rma_reasons']],
            ['route' => 'orders.trashed', 'prefixes' => ['orders'], 'excludes' => ['orders.index', 'orders.show']],
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
            ['route' => 'pages.index', 'prefixes' => ['pages']],
            ['route' => 'page_categories.index', 'prefixes' => ['page_categories']],
        ];

        return hook_filter('admin.sidebar.pages_routes', $routes);
    }

    /**
     * 获取报表子页面路由
     * @return mixed
     */
    public function getReportSubRoutes()
    {
        $routes = [
            ['route' => 'reports_sale.index', 'prefixes' => ['reports_sale']],
            ['route' => 'reports_view.index', 'prefixes' => ['reports_view']],
        ];

        return hook_filter('admin.sidebar.reports_routes', $routes);
    }

    /**
     * 获取设计子页面路由
     * @return mixed
     */
    public function getDesignSubRoutes()
    {
        $routes = [
            ['route' => 'theme.index', 'prefixes' => ['theme'], 'hide_mobile' => true],
            ['route' => 'design_menu.index', 'prefixes' => ['design_menu'], 'hide_mobile' => 1],
            ['route' => 'design.index', 'prefixes' => ['design'], 'blank' => true, 'hide_mobile' => true],
            ['route' => 'design_footer.index', 'prefixes' => ['design_footer'], 'blank' => true, 'hide_mobile' => true],
            ['route' => 'design_app_home.index', 'prefixes' => ['design_app_home'], 'blank' => false, 'hide_mobile' => true],
        ];

        return hook_filter('admin.sidebar.design_routes', $routes);
    }

    /**
     * 获取插件子页面路由
     * @return mixed
     */
    public function getPluginSubRoutes()
    {
        $types = collect(Plugin::TYPES);
        $types = $types->map(function ($item) {
            return 'plugins.' . $item;
        });

        $routes[] = ['route' => 'plugins.index', 'prefixes' => ['plugins'], 'excludes' => $types->toArray()];

        $originTypes = $types->push('plugins.index', 'plugins.edit')->push();
        foreach (Plugin::TYPES as $type) {
            $types    = $originTypes->reject("plugins.{$type}");
            $routes[] = ['route' => "plugins.{$type}", 'prefixes' => ['plugins'], 'title' => trans("admin/plugin.{$type}"), 'excludes' => $types->toArray()];
        }

        $routes[] = ['route' => 'marketing.index', 'prefixes' => ['marketing']];

        return hook_filter('admin.sidebar.plugins_routes', $routes);
    }

    /**
     * 获取系统设置子页面路由
     * @return mixed
     */
    public function getSettingSubRoutes()
    {
        $routes = [
            ['route' => 'settings.index', 'prefixes' => ['settings']],
            ['route' => 'account.index', 'prefixes' => ['account']],
            ['route' => 'admin_users.index', 'prefixes' => ['admin_users', 'admin_roles']],
            ['route' => 'regions.index', 'prefixes' => ['regions']],
            ['route' => 'tax_rates.index', 'prefixes' => ['tax_rates']],
            ['route' => 'tax_classes.index', 'prefixes' => ['tax_classes']],
            ['route' => 'currencies.index', 'prefixes' => ['currencies']],
            ['route' => 'languages.index', 'prefixes' => ['languages']],
            ['route' => 'countries.index', 'prefixes' => ['countries']],
            ['route' => 'zones.index', 'prefixes' => ['zones']],
        ];

        return hook_filter('admin.sidebar.setting_routes', $routes);
    }
}
