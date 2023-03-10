<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Illuminate\View\Component;

class Header extends Component
{
    public array $links = [];

    private ?AdminUser $adminUser;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminUser = current_user();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $sidebar       = new Sidebar();
        $preparedMenus = $this->prepareMenus();

        foreach ($preparedMenus as $menu) {
            $menuCode = $menu['code'] ?? '';
            if ($menuCode) {
                $routes          = [];
                $subRoutesMethod = "get{$menu['code']}SubRoutes";
                if (method_exists($sidebar, $subRoutesMethod)) {
                    $sideMenuRoutes = $sidebar->{"get{$menu['code']}SubRoutes"}();
                    foreach ($sideMenuRoutes as $route) {
                        $routeFirst  = explode('.', $route['route'])[0] ?? '';
                        $routes[]    = 'admin.' . $route['route'];
                        $routes[]    = 'admin.' . $routeFirst . '.edit';
                        $routes[]    = 'admin.' . $routeFirst . '.show';
                    }
                }

                $data = [
                    'menu_code' => $menuCode,
                    'routes'    => $routes,
                ];
                $filterRoutes = hook_filter('admin.components.header.routes', $data);
                $routes       = $filterRoutes['routes'] ?? [];
                if (empty($routes)) {
                    $is_route = equal_route('admin.' . $menu['route']);
                } else {
                    $is_route = equal_route($routes);
                }
            } else {
                $is_route = equal_route('admin.' . $menu['route']);
            }

            $this->addLink($menu['name'], $menu['route'], $is_route);
        }

        return view('admin::components.header');
    }

    /**
     * 默认菜单
     */
    private function prepareMenus()
    {
        $menus = [
            ['name' => trans('admin/common.home'), 'route' => 'home.index', 'code' => ''],
            ['name' => trans('admin/common.order'), 'route' => 'orders.index', 'code' => 'Order'],
            ['name' => trans('admin/common.product'), 'route' => 'products.index', 'code' => 'Product'],
            ['name' => trans('admin/common.customer'), 'route' => 'customers.index', 'code' => 'Customer'],
            ['name' => trans('admin/common.page'), 'route' => 'pages.index', 'code' => 'Page'],
            ['name' => trans('admin/common.setting'), 'route' => 'settings.index', 'code' => 'Setting'],
        ];

        return hook_filter('admin.header_menus', $menus);
    }

    /**
     * 添加后台顶部菜单链接
     *
     * @param $title
     * @param $route
     * @param false $active
     */
    private function addLink($title, $route, bool $active = false)
    {
        $permissionRoute = str_replace('.', '_', $route);
        if ($this->adminUser->cannot($permissionRoute) && $route != 'home.index') {
            return;
        }

        $url           = admin_route($route);
        $this->links[] = [
            'title'  => $title,
            'url'    => $url,
            'active' => $active,
        ];
    }
}
