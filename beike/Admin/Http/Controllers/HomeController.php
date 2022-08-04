<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\DashboardRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'product' => DashboardRepo::getProductData(),
            'order' => DashboardRepo::getOrderData(),
            'customer' => DashboardRepo::getCustomerData(),
            'total' => DashboardRepo::getTotalData(),
        ];

        return view('admin::pages.home', $data);
    }
}
