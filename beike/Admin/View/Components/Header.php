<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Illuminate\View\Component;

class Header extends Component
{
    public array $links = [];
    private AdminUser $adminUser;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminUser = auth()->user();
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $this->addLink('管理首页', 'home.index', equal_route('admin.home.index'));
        $this->addLink('订单管理', 'orders.index', equal_route('admin.orders.index'));
        $this->addLink('商品管理', 'products.index', equal_route('admin.products.index'));
        $this->addLink('会员管理', 'customers.index', equal_route('admin.customers.index'));
        $this->addLink('系统设置', 'settings.index', equal_route('admin.settings.index'));

        return view('admin::components.header');
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
