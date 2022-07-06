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

        if (Str::startsWith($routeName, ['admin.products.', 'admin.categories.'])) {
            $this->addLink('商品分类', admin_route('categories.index'), 'fa fa-tachometer-alt', false);
            $this->addLink('商品列表', admin_route('products.index'), 'fa fa-tachometer-alt', false);
            $this->addLink('回收站', admin_route('products.index', ['trashed' => 1]), 'fa fa-tachometer-alt', false);
        }

        if (Str::startsWith($routeName, ['admin.customers.', 'admin.customer_groups.'])) {
            $this->addLink('会员管理', admin_route('customers.index'), 'fa fa-tachometer-alt', false);
            $this->addLink('用户组', admin_route('customer_groups.index'), 'fa fa-tachometer-alt', false);
        }

        if (Str::startsWith($routeName, ['admin.orders.'])) {
            $this->addLink('订单列表', admin_route('orders.index'), 'fa fa-tachometer-alt', false);
        }

        return view('admin::components.sidebar');
    }

    public function addLink($title, $url, $icon, $active)
    {
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'active' => $active
        ];
    }
}
