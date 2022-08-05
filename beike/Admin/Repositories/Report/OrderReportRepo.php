<?php
/**
 * OrderRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-05 14:33:49
 * @modified   2022-08-05 14:33:49
 */

namespace Beike\Admin\Repositories\Report;


use Beike\Models\Order;
use Beike\Repositories\OrderRepo;
use Illuminate\Support\Facades\DB;

class OrderReportRepo
{
    public static function getLatestMonth()
    {
        $orders = OrderRepo::getListBuilder(['start' => today()->subDays(30), 'end' => today()->subDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        return $orders;
    }
}
