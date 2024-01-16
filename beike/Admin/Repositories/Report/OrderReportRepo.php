<?php
/**
 * OrderRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-05 14:33:49
 * @modified   2022-08-05 14:33:49
 */

namespace Beike\Admin\Repositories\Report;

use Beike\Models\Order;
use Beike\Models\Product;
use Beike\Models\ProductView;
use Beike\Repositories\OrderProductRepo;
use Beike\Repositories\OrderRepo;
use Beike\Services\StateMachineService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class OrderReportRepo
{
    /**
     * 获取最近一个月每日销售订单数
     * @return mixed
     */
    public static function getLatestMonth($statuses = [])
    {
        $statuses = $statuses ?: StateMachineService::getValidStatuses();

        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subMonth(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('DATE(created_at) as date, count(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subMonth(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('DATE(created_at) as date, sum(total) as amount'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $dates  = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subMonth(), today()->subDay())->toArray();
        foreach ($period as $date) {
            $dateFormat  = $date->format('Y-m-d');
            $orderTotal  = $orderTotals[$dateFormat]  ?? null;
            $orderAmount = $orderAmounts[$dateFormat] ?? null;

            $dates[]   = $dateFormat;
            $totals[]  = $orderTotal ? $orderTotal->total : 0;
            $amounts[] = $orderAmount ? $orderAmount->amount : 0;
        }

        $data = [
            'period'  => $dates,
            'totals'  => $totals,
            'amounts' => $amounts,
        ];

        return hook_filter('dashboard.order_report_month', $data);
    }

    /**
     * 获取最近一周每日销售订单数
     */
    public static function getLatestWeek($statuses = [])
    {
        $statuses    = $statuses ?: StateMachineService::getValidStatuses();
        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subWeek(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('DATE(created_at) as date, count(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subWeek(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('DATE(created_at) as date, sum(total) as amount'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $dates  = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subWeek(), today()->subDay())->toArray();
        foreach ($period as $date) {
            $dateFormat  = $date->format('Y-m-d');
            $orderTotal  = $orderTotals[$dateFormat]  ?? null;
            $orderAmount = $orderAmounts[$dateFormat] ?? null;

            $dates[]   = $dateFormat;
            $totals[]  = $orderTotal ? $orderTotal->total : 0;
            $amounts[] = $orderAmount ? $orderAmount->amount : 0;
        }

        $data = [
            'period'  => $dates,
            'totals'  => $totals,
            'amounts' => $amounts,
        ];

        return hook_filter('dashboard.order_report_week', $data);
    }

    /**
     * 获取最近一年每月销售订单数
     */
    public static function getLatestYear($statuses = [])
    {
        $statuses    = $statuses ?: StateMachineService::getValidStatuses();
        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subYear(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as total'))
            ->groupBy(['year', 'month'])
            ->get();
        $orderMonthTotals = [];
        foreach ($orderTotals as $orderTotal) {
            $key                    = Carbon::create($orderTotal->year, $orderTotal->month)->format('Y-m');
            $orderMonthTotals[$key] = $orderTotal['total'];
        }

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subYear(), 'end' => today(), 'statuses' => $statuses])
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, sum(total) as amount'))
            ->groupBy(['year', 'month'])
            ->get();
        $orderMonthAmounts = [];
        foreach ($orderAmounts as $orderAmount) {
            $key                     = Carbon::create($orderAmount->year, $orderAmount->month)->format('Y-m');
            $orderMonthAmounts[$key] = $orderAmount['amount'];
        }

        $dates  = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subYear()->endOfMonth(), '1 month', today()->endOfMonth())->toArray();
        foreach ($period as $date) {
            $dateFormat  = $date->format('Y-m');
            $orderTotal  = $orderMonthTotals[$dateFormat]  ?? null;
            $orderAmount = $orderMonthAmounts[$dateFormat] ?? null;

            $dates[]   = $dateFormat;
            $totals[]  = $orderTotal ?: 0;
            $amounts[] = $orderAmount ?: 0;
        }

        $data = [
            'period'  => $dates,
            'totals'  => $totals,
            'amounts' => $amounts,
        ];

        return hook_filter('dashboard.order_report_year', $data);
    }

    public static function getSaleInfoByProducts($order, $filter)
    {
        $filter['order'] = $order;

        return OrderProductRepo::getBuilder($filter)->get();
    }

    public static function getSaleInfoByCustomers($order, $filter)
    {
        $builder = Order::query()->with(['customer'])->where('customer_id', '>', 0);

        $order_statuses = $filters['order_statuses'] ?? StateMachineService::getValidStatuses();
        if ($order_statuses) {
            $builder->whereIn('status', StateMachineService::getValidStatuses());
        } else {
            $builder->where('status', '<>', StateMachineService::CREATED);
        }

        $start = $filter['start'] ?? null;
        if ($start) {
            $builder->where('created_at', '>=', $start);
        }

        $end = $filter['end'] ?? null;
        if ($end) {
            $builder->where('created_at', '<', Carbon::createFromFormat('Y-m-d', $end)->subDay());
        }

        if ($order) {
            $builder->orderBy($order, 'desc');
        }

        $limit = $filter['limit'] ?? null;
        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->groupBy('customer_id')
            ->selectRaw('`customer_id`, COUNT(*) AS order_count, SUM(`total`) AS order_amount')
            ->get();
    }

    /**
     * 获取所有商品的浏览数量列表
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getProductViews()
    {
        $builder = Product::query()->with(['description', 'views'])
            ->leftJoin('product_views', 'product_views.product_id', 'products.id')
            ->groupBy('products.id')
            ->select(['products.id'])
            ->selectRaw('COUNT(product_views.id) AS view_count')
            ->orderBy('view_count', 'desc');

        return $builder->get();
    }

    /**
     * 获取最近一个月的按天浏览数量统计表。 $productId有值则只统计该商品的浏览数量，不传则统计所有商品的浏览数量
     * @param $productId
     * @return array
     */
    public static function getViewsLatestMonth($productId = 0): array
    {
        $builder = ProductView::query()->where('created_at', '>=', today()->subMonth())
            ->where('created_at', '<', today()->addDay())
            ->select(DB::raw('DATE(created_at) as date, count(*) as pv_totals, COUNT(DISTINCT session_id) AS uv_totals'))
            ->groupBy('date');
        if ($productId) {
            $builder->where('product_id', $productId);
        }
        $totals = $builder->get()->toArray();
        $period = CarbonPeriod::create(today()->subMonth(), today()->subDay())->toArray();

        $mapPvTotals = array_column($totals, 'pv_totals', 'date');
        $mapUvTotals = array_column($totals, 'uv_totals', 'date');
        $pvTotals    = [];
        $uvTotals    = [];
        foreach ($period as $date) {
            $dateFormat            = $date->format('Y-m-d');
            $pvTotals[$dateFormat] = $mapPvTotals[$dateFormat] ?? 0;
            $uvTotals[$dateFormat] = $mapUvTotals[$dateFormat] ?? 0;
        }

        $data = [
            'pv_totals' => $pvTotals,
            'uv_totals' => $uvTotals,
        ];

        return hook_filter('report.order_report_views_month', $data);
    }

    /**
     * 获取最近一周的按天浏览数量统计表。 $productId有值则只统计该商品的浏览数量，不传则统计所有商品的浏览数量
     * @param $productId
     * @return array
     */
    public static function getViewsLatestWeek($productId = 0): array
    {
        $builder = ProductView::query()->where('created_at', '>=', today()->subWeek())
            ->where('created_at', '<', today()->addDay())
            ->select(DB::raw('DATE(created_at) as date, count(*) as pv_totals, COUNT(DISTINCT session_id) AS uv_totals'))
            ->groupBy('date');
        if ($productId) {
            $builder->where('product_id', $productId);
        }
        $totals = $builder->get()->toArray();
        $period = CarbonPeriod::create(today()->subWeek(), today()->subDay())->toArray();

        $mapPvTotals = array_column($totals, 'pv_totals', 'date');
        $mapUvTotals = array_column($totals, 'uv_totals', 'date');
        $pvTotals    = [];
        $uvTotals    = [];
        foreach ($period as $date) {
            $dateFormat            = $date->format('Y-m-d');
            $pvTotals[$dateFormat] = $mapPvTotals[$dateFormat] ?? 0;
            $uvTotals[$dateFormat] = $mapUvTotals[$dateFormat] ?? 0;
        }

        $data = [
            'pv_totals' => $pvTotals,
            'uv_totals' => $uvTotals,
        ];

        return hook_filter('report.order_report_views_week', $data);
    }

    /**
     * 获取最近一年的按月浏览数量统计表。 $productId有值则只统计该商品的浏览数量，不传则统计所有商品的浏览数量
     * @param $productId
     * @return array
     */
    public static function getViewsLatestYear($productId = 0): array
    {
        $builder = ProductView::query()->where('created_at', '>=', today()->subYear())
            ->where('created_at', '<', today()->addDay())
            ->select(DB::raw('CONCAT(YEAR(created_at), \'-\', MONTH(created_at)) AS ym, count(*) as pv_totals, COUNT(DISTINCT session_id) AS uv_totals'))
            ->groupBy(['ym']);
        if ($productId) {
            $builder->where('product_id', $productId);
        }

        $totals = $builder->get()->toArray();
        $period = CarbonPeriod::create(today()->subYear()->endOfMonth(), '1 month', today()->endOfMonth())->toArray();

        $mapPvTotals = array_column($totals, 'pv_totals', 'ym');
        $mapUvTotals = array_column($totals, 'uv_totals', 'ym');
        $pvTotals    = [];
        $uvTotals    = [];
        foreach ($period as $total) {
            $key            = Carbon::create($total->year, $total->month)->format('Y-m');
            $pvTotals[$key] = $mapPvTotals[$key] ?? 0;
            $uvTotals[$key] = $mapUvTotals[$key] ?? 0;
        }

        $data = [
            'pv_totals' => $pvTotals,
            'uv_totals' => $uvTotals,
        ];

        return hook_filter('report.order_report_views_year', $data);
    }
}
