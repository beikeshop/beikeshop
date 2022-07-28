<?php
/**
 * OrderTotalRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-28 10:30:03
 * @modified   2022-07-28 10:30:03
 */

namespace Beike\Repositories;

use Beike\Models\Order;

class OrderTotalRepo
{
    public static function createTotals(Order $order, array $totals)
    {
        $items = [];
        foreach ($totals as $total) {
            $items[] = [
                'code' => $total['code'],
                'value' => $total['amount'],
                'title' => $total['title'],
                'reference' => json_encode($total['reference'] ?? ''),
            ];
        }
        $order->orderTotals()->createMany($items);
    }
}
