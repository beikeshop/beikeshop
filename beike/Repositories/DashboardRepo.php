<?php
/**
 * DashboardRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-03 18:16:53
 * @modified   2022-08-03 18:16:53
 */

namespace Beike\Repositories;

use Beike\Models\Product;
use Beike\Models\Customer;

class DashboardRepo
{
    /**
     * 获取产品总数
     *
     * @return array
     */
    public static function getProductData(): array
    {
        return [
            'total' => quantity_format(Product::query()->count()),
            'percentage' => 0,
        ];
    }


    /**
     * 获取订单基础统计, 总数和今日昨日比较
     *
     * @return array
     */
    public static function getOrderData(): array
    {
        $today = OrderRepo::getListBuilder(['start' => today(), 'end' => today()->addDay()])->count();
        $yesterday = OrderRepo::getListBuilder(['start' => today()->subDay(), 'end' => today()])->count();
        $difference = $today - $yesterday;
        if ($difference && $yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } else {
            $percentage = 0;
        }

        return [
            'total' => quantity_format(Product::query()->count()),
            'percentage' => $percentage,
        ];
    }


    /**
     * 获取客户基础统计, 总数和今日昨日比较
     *
     * @return array
     */
    public static function getCustomerData(): array
    {
        $today = CustomerRepo::getListBuilder(['start' => today(), 'end' => today()->addDay()])->count();
        $yesterday = CustomerRepo::getListBuilder(['start' => today()->subDay(), 'end' => today()])->count();
        $difference = $today - $yesterday;
        if ($difference && $yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } else {
            $percentage = 0;
        }

        return [
            'total' => quantity_format(Customer::query()->count()),
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
        $today = OrderRepo::getListBuilder(['start' => today(), 'end' => today()->addDay()])->sum('total');
        $yesterday = OrderRepo::getListBuilder(['start' => today()->subDay(), 'end' => today()])->sum('total');
        $difference = $today - $yesterday;
        if ($difference && $yesterday) {
            $percentage = round(($difference / $yesterday) * 100);
        } else {
            $percentage = 0;
        }

        return [
            'total' => quantity_format(Customer::query()->count()),
            'percentage' => $percentage,
        ];
    }
}
