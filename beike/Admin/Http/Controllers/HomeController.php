<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\DashboardRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'views' => DashboardRepo::getCustomerViewData(),
            'orders' => DashboardRepo::getOrderData(),
            'customers' => DashboardRepo::getCustomerData(),
            'order_totals' => DashboardRepo::getTotalData(),
        ];

        return view('admin::pages.home', $data);
    }
}
