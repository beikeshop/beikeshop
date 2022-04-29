<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Sidebar extends Component
{
    protected array $links;

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
        $this->addLink('商品分类', admin_route('products.index'), 'fa fa-tachometer-alt', false);
        $this->addLink('商品列表', admin_route('products.index'), 'fa fa-tachometer-alt', false);
        $this->addLink('回收站', admin_route('products.index'), 'fa fa-tachometer-alt', false);

        $data = [
            'links' => $this->links,
        ];
        return view('components.admin.sidebar', $data);
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
