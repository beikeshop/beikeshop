<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Repositories\DashboardRepo;
use Beike\Admin\Repositories\Report\OrderReportRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'views' => DashboardRepo::getCustomerViewData(),
            'orders' => DashboardRepo::getOrderData(),
            'customers' => DashboardRepo::getCustomerData(),
            'order_totals' => DashboardRepo::getTotalData(),
            'order_trends' => [
                'latest_month' => OrderReportRepo::getLatestMonth(),
                'latest_week' => OrderReportRepo::getLatestWeek(),
                'latest_year' => OrderReportRepo::getLatestYear(),
            ]
        ];

        return view('admin::pages.home', $data);
    }
}
