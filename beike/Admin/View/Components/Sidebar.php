<?php

namespace Beike\Admin\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links = [];
    private string $adminName;
    private string $routeNameWithPrefix;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminName = admin_name();
        $this->routeNameWithPrefix = request()->route()->getName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return mixed
     */
    public function render()
    {
        $adminName = $this->adminName;
        $routeNameWithPrefix = request()->route()->getName();
        $routeName = str_replace($adminName . '.', '', $routeNameWithPrefix);

        if (Str::startsWith($routeName, ['home.'])) {
            $this->addLink('首页装修', admin_route('design.index'), 'fa fa-tachometer-alt', $this->equalRoute('design.index'), true);
            $this->addLink('插件列表', admin_route('plugins.index'), 'fa fa-tachometer-alt', $this->equalRoute('plugins.index'));
        }

        if (Str::startsWith($routeName, ['products.', 'categories.', 'brands.'])) {
            $this->addLink('商品分类', admin_route('categories.index'), 'fa fa-tachometer-alt', $this->equalRoute('categories.index'));
            $this->addLink('商品列表', admin_route('products.index'), 'fa fa-tachometer-alt', $this->equalRoute('products.index'));
            $this->addLink('品牌管理', admin_route('brands.index'), 'fa fa-tachometer-alt', $this->equalRoute('brands.index'));
            $this->addLink('回收站', admin_route('products.index', ['trashed' => 1]), 'fa fa-tachometer-alt', false);
        }

        if (Str::startsWith($routeName, ['customers.', 'customer_groups.'])) {
            $this->addLink('会员管理', admin_route('customers.index'), 'fa fa-tachometer-alt', $this->equalRoute('customers.index'));
            $this->addLink('用户组', admin_route('customer_groups.index'), 'fa fa-tachometer-alt', $this->equalRoute('customer_groups.index'));
        }

        if (Str::startsWith($routeName, ['orders.'])) {
            $this->addLink('订单列表', admin_route('orders.index'), 'fa fa-tachometer-alt', $this->equalRoute('orders.index'));
        }

        if (Str::startsWith($routeName, ['settings.', 'plugins.', 'tax_classes', 'tax_rates', 'regions'])) {
            $this->addLink('系统设置', admin_route('settings.index'), 'fa fa-tachometer-alt', $this->equalRoute('settings.index'));
            $this->addLink('插件列表', admin_route('plugins.index'), 'fa fa-tachometer-alt', $this->equalRoute('plugins.index'));
            $this->addLink('区域分组', admin_route('regions.index'), 'fa fa-tachometer-alt', $this->equalRoute('regions.index'));
            $this->addLink('税率设置', admin_route('tax_rates.index'), 'fa fa-tachometer-alt', $this->equalRoute('tax_rates.index'));
            $this->addLink('税费类别', admin_route('tax_classes.index'), 'fa fa-tachometer-alt', $this->equalRoute('tax_classes.index'));
            $this->addLink('首页装修', admin_route('design.index'), 'fa fa-tachometer-alt', $this->equalRoute('design.index'), true);
        }

        return view('admin::components.sidebar');
    }


    /**
     * 添加链接
     *
     * @param $title
     * @param $url
     * @param $icon
     * @param $active
     * @param false $newWindow
     */
    public function addLink($title, $url, $icon, $active, bool $newWindow = false)
    {
        $this->links[] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'active' => $active,
            'new_window' => $newWindow
        ];
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
