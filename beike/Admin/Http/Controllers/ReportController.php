<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Admin\Repositories\Report\OrderReportRepo;
use Beike\Services\StateMachineService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function sale(Request $request): mixed
    {
        $statuses = $request->get('statuses') ? explode(',', $request->get('statuses')) : StateMachineService::getValidStatuses();

        $filter = [
            'order_statuses' => $statuses,
            'date_start'     => $request->get('start'),
            'date_end'       => $request->get('end'),
            'limit'          => 10,
        ];
        $data = [
            'statuses_selected'        => $statuses,
            'statuses'                 => StateMachineService::getAllStatuses(),
            'quantity_by_products'     => OrderReportRepo::getSaleInfoByProducts('total_quantity', $filter)->toArray(), // 商品销量排行
            'amount_by_products'       => OrderReportRepo::getSaleInfoByProducts('total_amount', $filter)->toArray(), // 商品金额排行
            'amount_by_customers'      => OrderReportRepo::getSaleInfoByCustomers('order_amount', $filter)->toArray(),  // 用户购买金额排行
            'order_trends'             => [
                'latest_month' => OrderReportRepo::getLatestMonth($statuses),
                'latest_year'  => OrderReportRepo::getLatestYear($statuses),
            ],
        ];

        return view('admin::pages.report.sale', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request): mixed
    {
        $data = [
            'views'        => OrderReportRepo::getProductViews(),
            'views_trends' => [
                'latest_month' => OrderReportRepo::getViewsLatestMonth(),
                'latest_week'  => OrderReportRepo::getViewsLatestWeek(),
                'latest_year'  => OrderReportRepo::getViewsLatestYear(),
            ],
        ];

        return view('admin::pages.report.product_view', $data);
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
