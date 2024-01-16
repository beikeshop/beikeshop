<?php

namespace Beike\Admin\View\Components;

use Beike\Models\AdminUser;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Header extends Component
{
    public array $links = [];

    private ?AdminUser $adminUser;

    public array $commonLinks;

    public array $historyLinks;

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
        $this->commonLinks  = $this->getCommonLinks();
        $this->historyLinks = $this->handleHistoryLinks();

        return view('admin::components.header');
    }

    /**
     * 常用功能链接
     */
    private function getCommonLinks()
    {
        $commonLinks = [
            ['route' => 'design.index', 'icon'  => 'bi bi-palette', 'blank' => true],
            ['route' => 'design_footer.index', 'icon' => 'bi bi-palette', 'blank' => true],
            ['route' => 'design_menu.index', 'icon' => 'bi bi-list', 'blank' => false],
            ['route' => 'languages.index', 'icon' => 'bi bi-globe2', 'blank' => false],
            ['route' => 'currencies.index', 'icon' => 'bi bi-currency-dollar', 'blank' => false],
            ['route' => 'plugins.index', 'icon' => 'bi bi-plug', 'blank' => false],
        ];

        foreach ($commonLinks as $index => $commonLink) {
            $route                        = $commonLink['route'];
            $permissionRoute              = str_replace('.', '_', $route);
            $commonLinks[$index]['url']   = admin_route($route);
            $commonLinks[$index]['title'] = trans("admin/common.{$permissionRoute}");
        }

        return hook_filter('admin.components.header.common_links', $commonLinks);
    }

    /**
     * 处理最近访问链接
     */
    private function handleHistoryLinks(): array
    {
        $links     = [];
        $histories = $this->getHistoryRoutesFromSession();
        foreach ($histories as $history) {
            $routeName       = str_replace(admin_name() . '.', '', $history);
            $permissionRoute = str_replace('.', '_', $routeName);

            if (stripos($routeName, 'plugins.') !== false) {
                $type  = str_replace('plugins.', '', $routeName);
                if ($type == 'index') {
                    $title = trans("admin/common.{$permissionRoute}");
                } else {
                    $title = trans("admin/plugin.{$type}");
                }
            } else {
                $title = trans("admin/common.{$permissionRoute}");
            }

            if (stripos($title, 'admin/common.') !== false) {
                $routeName     = str_replace(['ies.index', 'ies.create'], 'y', $routeName);
                $tempRouteName = str_replace(['s.index', 's.create'], '', $routeName);
                $title         = trans("admin/common.{$tempRouteName}");
            }

            try {
                $url = admin_route($routeName);
            } catch (\Exception $e) {
                $url = '';
            }
            if (empty($url)) {
                continue;
            }

            $links[] = [
                'route' => $routeName,
                'url'   => $url,
                'title' => $title,
            ];
        }

        return $links;
    }

    /**
     * 从 session 获取最近访问的链接
     *
     * @return array
     */
    private function getHistoryRoutesFromSession(): array
    {
        $histories = session('histories', []);

        $currentRoute = request()->route()->getName();
        $routeName    = str_replace(admin_name() . '.', '', $currentRoute);

        if (in_array($routeName, ['edit.locale', 'home.menus'])) {
            return $histories;
        }

        array_unshift($histories, $currentRoute);
        $histories = array_slice(array_unique($histories), 0, 6);
        session(['histories' => $histories]);

        return $histories;
    }
}
