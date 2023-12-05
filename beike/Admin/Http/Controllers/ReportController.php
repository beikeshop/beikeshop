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
            'quantity_by_products'     => OrderReportRepo::getSaleInfoByProducts('total_quantity', 10, $request->get('start'), $request->get('end'))->toArray(), // 商品销量排行
            'amount_by_products'     => OrderReportRepo::getSaleInfoByProducts('total_amount', 10, $request->get('start'), $request->get('end'))->toArray(), // 商品金额排行
            'amount_by_customers'    => OrderReportRepo::getSaleInfoByCustomers('order_amount', 10, $request->get('start'), $request->get('end'))->toArray(),  // 用户购买金额排行
            'order_trends' => [
                'latest_month' => OrderReportRepo::getLatestMonth(),
                'latest_week'  => OrderReportRepo::getLatestWeek(),
                'latest_year'  => OrderReportRepo::getLatestYear(),
            ],
        ];

        return view('admin::pages.report.sale', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function vieidw(Request $request): mixed
    {
        $data = [
            'views' => OrderReportRepo::getProductViews(),
            'views_trends' => [
                'latest_month' => OrderReportRepo::getViewsLatestMonth(),
                'latest_week'  => OrderReportRepo::getViewsLatestWeek(),
                'latest_year'  => OrderReportRepo::getViewsLatestYear(),
            ],
        ];

        return view('admin::pages.report.view', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function productView(Request $request, int $productId): mixed
    {
        $data = [
            'views_trends' => [
                'latest_month' => OrderReportRepo::getViewsLatestMonth($productId),
                'latest_week'  => OrderReportRepo::getViewsLatestWeek($productId),
                'latest_year'  => OrderReportRepo::getViewsLatestYear($productId),
            ],
        ];

        return json_success(trans('common.get_success'), $data);
    }
}
