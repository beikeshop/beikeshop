<?php

namespace Beike\Admin\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $routeName = request()->route()->getName();
        if (Str::startsWith($routeName, [admin_name() . '.products.', admin_name() . '.categories.'])) {
            $this->addLink('商品分类', admin_route('categories.index'), 'fa fa-tachometer-alt', false);
            $this->addLink('商品列表', admin_route('products.index'), 'fa fa-tachometer-alt', false);
            $this->addLink('回收站', admin_route('products.index', ['trashed' => 1]), 'fa fa-tachometer-alt', false);
        }

        // if (Str::startsWith($routeName, [admin_name() . '.plugins.'])) {
        //     $this->addLink('插件列表', admin_route('categories.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.plugins.index');
        // }

        if (Str::startsWith($routeName, [admin_name() . '.customers.', admin_name() . '.customer_groups.'])) {
            $this->addLink('会员管理', admin_route('customers.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.customers.index');
            $this->addLink('用户组', admin_route('customer_groups.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.customer_groups.index');
        }

        if (Str::startsWith($routeName, [admin_name() . '.orders.'])) {
            $this->addLink('订单列表', admin_route('orders.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.orders.index');
        }

        if (Str::startsWith($routeName, [admin_name() . '.settings.', admin_name() . '.plugins.'])) {
            $this->addLink('系统设置', admin_route('settings.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.settings.index');
            $this->addLink('插件列表', admin_route('plugins.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.plugins.index');
            $this->addLink('首页装修', admin_route('design.index'), 'fa fa-tachometer-alt', $routeName == admin_name() . '.design.index', true);
        }

        return view('admin::components.sidebar');
    }

    public function addLink($title, $url, $icon, $active, $new_window = false)
    {
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'active' => $active,
            'new_window' => $new_window ?? false
        ];
    }
}
