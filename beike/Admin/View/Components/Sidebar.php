<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links = [];
    private string $adminName;
    private ?string $routeNameWithPrefix;
    private ?AdminUser $adminUser;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->adminName = admin_name();
        $this->routeNameWithPrefix = request()->route()->getName();
        $this->adminUser = current_user();
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
            $this->addLink('design.index', 'fa fa-tachometer-alt', $this->equalRoute('design.index'), true);
            $this->addLink('plugins.index', 'fa fa-tachometer-alt', $this->equalRoute('plugins.index'));
            $this->addLink('categories.index', 'fa fa-tachometer-alt', $this->equalRoute('categories.index'));
            $this->addLink('brands.index', 'fa fa-tachometer-alt', $this->equalRoute('brands.index'));
            $this->addLink('tax_rates.index', 'fa fa-tachometer-alt', $this->equalRoute('tax_rates.index'));
            $this->addLink('tax_rates.index', 'fa fa-tachometer-alt', $this->equalRoute('tax_rates.index'));
            $this->addLink('currencies.index', 'fa fa-tachometer-alt', $this->equalRoute('currencies.index'));
        }

        if (Str::startsWith($routeName, ['products.', 'categories.', 'brands.'])) {
            $this->addLink('categories.index', 'fa fa-tachometer-alt', $this->equalRoute('categories.index'));
            $this->addLink('products.index', 'fa fa-tachometer-alt', $this->equalRoute('products.index'));
            $this->addLink('brands.index', 'fa fa-tachometer-alt', $this->equalRoute('brands.index'));
            $this->addLink('products.trashed', 'fa fa-tachometer-alt', $this->equalRoute('products.trashed'));
        }

        if (Str::startsWith($routeName, ['customers.', 'customer_groups.'])) {
            $this->addLink('customers.index', 'fa fa-tachometer-alt', $this->equalRoute('customers.index'));
            $this->addLink('customer_groups.index', 'fa fa-tachometer-alt', $this->equalRoute('customer_groups.index'));
        }

        if (Str::startsWith($routeName, ['orders.', 'rmas.'])) {
            $this->addLink('orders.index', 'fa fa-tachometer-alt', $this->equalRoute('orders.index'));
            $this->addLink('rmas.index', 'fa fa-tachometer-alt', $this->equalRoute('rmas.index'));
        }

        if (Str::startsWith($routeName, ['settings.', 'admin_users.', 'admin_roles.', 'plugins.', 'tax_classes', 'tax_rates', 'regions', 'currencies'])) {
            $this->addLink('settings.index', 'fa fa-tachometer-alt', $this->equalRoute('settings.index'));
            $this->addLink('admin_users.index', 'fa fa-tachometer-alt', $this->equalRoute('admin_users.index'));
            $this->addLink('plugins.index', 'fa fa-tachometer-alt', $this->equalRoute('plugins.index'));
            $this->addLink('regions.index', 'fa fa-tachometer-alt', $this->equalRoute('regions.index'));
            $this->addLink('tax_rates.index', 'fa fa-tachometer-alt', $this->equalRoute('tax_rates.index'));
            $this->addLink('tax_classes.index', 'fa fa-tachometer-alt', $this->equalRoute('tax_classes.index'));
            $this->addLink('currencies.index', 'fa fa-tachometer-alt', $this->equalRoute('currencies.index'));
            $this->addLink('design.index', 'fa fa-tachometer-alt', $this->equalRoute('design.index'), true);
        }

        return view('admin::components.sidebar');
    }


    /**
     * 添加左侧菜单链接
     *
     * @param $route
     * @param $icon
     * @param $active
     * @param false $newWindow
     */
    public function addLink($route, $icon, $active, bool $newWindow = false)
    {
        $permissionRoute = str_replace('.', '_', $route);
        if ($this->adminUser->cannot($permissionRoute)) {
            return;
        }

        $title = trans("admin/common.{$permissionRoute}");
        $url = admin_route($route);
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
