<?php
/**
 * DashboardRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-03 18:16:53
 * @modified   2022-08-03 18:16:53
 */

namespace Beike\Admin\Repositories;

use Beike\Repositories\CustomerRepo;
use Beike\Repositories\OrderRepo;
use Beike\Repositories\ProductRepo;

class DashboardRepo
{
    /**
     * 获取商品总数
     *
     * @return array
     * @throws \Exception
     */
    public static function getProductData(): array
    {
        $today      = ProductRepo::getBuilder(['created_start' => today()->startOfDay(), 'created_end' => today()->endOfDay()])->count();
        $yesterday  = ProductRepo::getBuilder(['created_start' => today()->subDay()->startOfDay(), 'created_end' => today()->subDay()->endOfDay()])->count();
        $difference = $today - $yesterday;
        if ($yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } elseif (empty($difference)) {
            $percentage = 0;
        } else {
            $percentage = 100;
        }

        return [
            'today'      => $today,
            'yesterday'  => $yesterday,
            'percentage' => $percentage,
        ];
    }

    /**
     * 获取客户访问统计今日昨日比较
     * @return array
     * @todo
     */
    public static function getCustomerViewData(): array
    {
        $today      = 10;
        $yesterday  = 8;
        $difference = $today - $yesterday;
        if ($yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } elseif (empty($difference)) {
            $percentage = 0;
        } else {
            $percentage = 100;
        }

        return [
            'today'      => $today,
            'yesterday'  => $yesterday,
            'percentage' => $percentage,
        ];
    }

    /**
     * 获取订单基础统计, 总数和今日昨日比较
     *
     * @return array
     */
    public static function getOrderData(): array
    {
        $today      = OrderRepo::getListBuilder(['start' => today()->startOfDay(), 'end' => today()->endOfDay()])->count();
        $yesterday  = OrderRepo::getListBuilder(['start' => today()->subDay()->startOfDay(), 'end' => today()->subDay()->endOfDay()])->count();
        $difference = $today - $yesterday;
        if ($yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } elseif (empty($difference)) {
            $percentage = 0;
        } else {
            $percentage = 100;
        }

        return [
            'today'      => $today,
            'yesterday'  => $yesterday,
            'percentage' => $percentage,
        ];
    }

    /**
     * 获取客户注册今日昨日比较
     *
     * @return array
     */
    public static function getCustomerData(): array
    {
        $today      = CustomerRepo::getListBuilder(['start' => today()->startOfDay(), 'end' => today()->endOfDay()])->count();
        $yesterday  = CustomerRepo::getListBuilder(['start' => today()->subDay()->startOfDay(), 'end' => today()->subDay()->endOfDay()])->count();
        $difference = $today - $yesterday;
        if ($yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } elseif (empty($difference)) {
            $percentage = 0;
        } else {
            $percentage = 0;
        }

        return [
            'today'      => $today,
            'yesterday'  => $yesterday,
            'percentage' => $percentage,
        ];
    }

    /**
     * 获取订单总额基础统计, 总数和今日昨日比较
     *
     * @return array
     */
    public static function getTotalData(): array
    {
        $today      = OrderRepo::getListBuilder(['start' => today()->startOfDay(), 'end' => today()->endOfDay()])->sum('total');
        $yesterday  = OrderRepo::getListBuilder(['start' => today()->subDay()->startOfDay(), 'end' => today()->subDay()->endOfDay()])->sum('total');
        $difference = $today - $yesterday;
        if ($yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } elseif (empty($difference)) {
            $percentage = 0;
        } else {
            $percentage = 100;
        }

        return [
            'today'      => currency_format($today),
            'yesterday'  => currency_format($yesterday),
            'percentage' => $percentage,
        ];
    }
}
