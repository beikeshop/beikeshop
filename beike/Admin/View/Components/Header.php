<?php

namespace Beike\Admin\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public array $links = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->addLink('管理首页', admin_route('home.index'), equal_route('admin.home.index'));
        $this->addLink('订单管理', admin_route('orders.index'), equal_route('admin.orders.index'));
        $this->addLink('商品管理', admin_route('products.index'), equal_route('admin.products.index'));
        $this->addLink('会员管理', admin_route('customers.index'), equal_route('admin.customers.index'));
        // $this->addLink('营销管理', admin_route('home.index'), equal_route('admin.promotions.index'));
        // $this->addLink('插件管理', admin_route('plugins.index'), equal_route('admin.plugins.index'));
        // $this->addLink('首页装修', admin_route('design.index'), equal_route('admin.design.index'));
        $this->addLink('系统设置', admin_route('settings.index'), equal_route('admin.settings.index'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin::components.header');
    }

    public function addLink($title, $url, $active = false)
    {
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'active' => $active
        ];
    }
}
