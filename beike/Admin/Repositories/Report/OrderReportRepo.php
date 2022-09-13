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
use Beike\Repositories\OrderRepo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class OrderReportRepo
{
    /**
     * 获取最近一个月每日销售订单数
     * @return mixed
     */
    public static function getLatestMonth()
    {
        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subMonth(), 'end' => today()])
            ->select(DB::raw('DATE(created_at) as date, count(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subMonth(), 'end' => today()])
            ->select(DB::raw('DATE(created_at) as date, sum(total) as amount'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $dates = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subMonth(), today()->subDay())->toArray();
        foreach ($period as $date) {
            $dateFormat = $date->format('Y-m-d');
            $orderTotal = $orderTotals[$dateFormat] ?? null;
            $orderAmount = $orderAmounts[$dateFormat] ?? null;

            $dates[] = $dateFormat;
            $totals[] = $orderTotal ? $orderTotal->total : 0;
            $amounts[] = $orderAmount ? $orderAmount->amount : 0;
        }

        $data = [
            'period' => $dates,
            'totals' => $totals,
            'amounts' => $amounts,
        ];
        return hook_filter('dashboard.order_report_month', $data);
    }


    /**
     * 获取最近一周每日销售订单数
     */
    public static function getLatestWeek()
    {
        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subWeek(), 'end' => today()])
            ->select(DB::raw('DATE(created_at) as date, count(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subWeek(), 'end' => today()])
            ->select(DB::raw('DATE(created_at) as date, sum(total) as amount'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $dates = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subWeek(), today()->subDay())->toArray();
        foreach ($period as $date) {
            $dateFormat = $date->format('Y-m-d');
            $orderTotal = $orderTotals[$dateFormat] ?? null;
            $orderAmount = $orderAmounts[$dateFormat] ?? null;

            $dates[] = $dateFormat;
            $totals[] = $orderTotal ? $orderTotal->total : 0;
            $amounts[] = $orderAmount ? $orderAmount->amount : 0;
        }

        $data = [
            'period' => $dates,
            'totals' => $totals,
            'amounts' => $amounts,
        ];
        return hook_filter('dashboard.order_report_week', $data);
    }


    /**
     * 获取最近一年每月销售订单数
     */
    public static function getLatestYear()
    {
        $orderTotals = OrderRepo::getListBuilder(['start' => today()->subYear(), 'end' => today()])
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as total'))
            ->groupBy(['year', 'month'])
            ->get();
        $orderMonthTotals = [];
        foreach ($orderTotals as $orderTotal) {
            $key = Carbon::create($orderTotal->year, $orderTotal->month)->format('Y-m');
            $orderMonthTotals[$key] = $orderTotal['total'];
        }

        $orderAmounts = OrderRepo::getListBuilder(['start' => today()->subYear(), 'end' => today()])
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, sum(total) as amount'))
            ->groupBy(['year', 'month'])
            ->get();
        $orderMonthAmounts = [];
        foreach ($orderAmounts as $orderAmount) {
            $key = Carbon::create($orderAmount->year, $orderAmount->month)->format('Y-m');
            $orderMonthAmounts[$key] = $orderAmount['amount'];
        }

        $dates = $totals = $amounts = [];
        $period = CarbonPeriod::create(today()->subYear()->endOfMonth(), '1 month', today()->endOfMonth())->toArray();
        foreach ($period as $date) {
            $dateFormat = $date->format('Y-m');
            $orderTotal = $orderMonthTotals[$dateFormat] ?? null;
            $orderAmount = $orderMonthAmounts[$dateFormat] ?? null;

            $dates[] = $dateFormat;
            $totals[] = $orderTotal ?: 0;
            $amounts[] = $orderAmount ?: 0;
        }

        $data = [
            'period' => $dates,
            'totals' => $totals,
            'amounts' => $amounts,
        ];
        return hook_filter('dashboard.order_report_year', $data);
    }
}
