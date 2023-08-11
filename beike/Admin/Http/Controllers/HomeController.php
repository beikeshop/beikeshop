<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Repositories\DashboardRepo;
use Beike\Admin\Repositories\Report\OrderReportRepo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(): mixed
    {
        $data = [
            'products'     => DashboardRepo::getProductData(),
            'orders'       => DashboardRepo::getOrderData(),
            'customers'    => DashboardRepo::getCustomerData(),
            'order_totals' => DashboardRepo::getTotalData(),
            'order_trends' => [
                'latest_month' => OrderReportRepo::getLatestMonth(),
                'latest_week'  => OrderReportRepo::getLatestWeek(),
                'latest_year'  => OrderReportRepo::getLatestYear(),
            ],
        ];

        return view('admin::pages.home', $data);
    }

    /**
     * 通过关键字搜索菜单
     *
     * @return array
     */
    public function menus()
    {
        $keyword = trim(request('keyword'));
        $menus   = [];
        $routes  = Route::getRoutes();
        foreach ($routes as $route) {
            $routeName = $route->getName();
            if (! Str::startsWith($routeName, 'admin')) {
                continue;
            }

            $method = $route->methods()[0];
            if ($method != 'GET') {
                continue;
            }

            $routeName       = str_replace('admin.', '', $routeName);
            $permissionRoute = str_replace('.', '_', $routeName);

            try {
                $url = admin_route($routeName);
            } catch (\Exception $e) {
                $url = '';
            }
            if (empty($url)) {
                continue;
            }

            $title = trans("admin/common.{$permissionRoute}");
            if (stripos($title, 'admin/common.') !== false) {
                continue;
            }

            if ($keyword && stripos($title, $keyword) !== false) {
                $menus[] = [
                    'route' => $routeName,
                    'url'   => admin_route($routeName),
                    'title' => $title,
                ];
            }
        }

        return $menus;
    }
}
