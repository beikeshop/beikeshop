<?php

namespace Beike\View\Components\Admin;

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
        $this->addLink('管理首页', admin_route('home.index'), true);
        $this->addLink('订单管理', admin_route('home.index'));
        $this->addLink('商品管理', admin_route('products.index'));
        $this->addLink('会员管理', admin_route('home.index'));
        $this->addLink('营销管理', admin_route('home.index'));
        $this->addLink('系统设置', admin_route('home.index'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('beike::components.admin.header');
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
