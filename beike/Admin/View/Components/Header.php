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
        $preparedMenus = $this->prepareMenus();
        foreach ($preparedMenus as $menu) {
            $this->addLink($menu['name'], $menu['route'], equal_route("admin.{$menu['name']}"));
        }
        return view('admin::components.header');
    }


    /**
     * 默认菜单
     */
    private function prepareMenus()
    {
        $menus = [
            ['name' => trans('admin/header.home'), 'route' => 'home.index'],
            ['name' => trans('admin/header.order'), 'route' => 'orders.index'],
            ['name' => trans('admin/header.product'), 'route' => 'products.index'],
            ['name' => trans('admin/header.customer'), 'route' => 'customers.index'],
            ['name' => trans('admin/header.setting'), 'route' => 'settings.index'],
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

        $url = admin_route($route);
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'active' => $active
        ];
    }
}
