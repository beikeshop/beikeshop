<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Repositories\Report\OrderReportRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function sale(Request $request): mixed
    {
        $data = [
            'quantity_by_products'     => OrderReportRepo::getSaleInfoByProducts('total_quantity', 10, $request->get('start'), $request->get('end'))->toArray(),
            'amount_by_products'     => OrderReportRepo::getSaleInfoByProducts('total_amount', 10, $request->get('start'), $request->get('end'))->toArray(),
            'amount_by_customers'    => OrderReportRepo::getSaleInfoByCustomers('order_amount', 10, $request->get('start'), $request->get('end'))->toArray(),
            'order_trends' => [
                'latest_month' => OrderReportRepo::getLatestMonth(),
                'latest_week'  => OrderReportRepo::getLatestWeek(),
                'latest_year'  => OrderReportRepo::getLatestYear(),
            ],
        ];

        return view('admin::pages.report.sale', $data);
    }
}
