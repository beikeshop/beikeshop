<?php
/**
 * OrderRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-04 17:22:02
 * @modified   2022-07-04 17:22:02
 */

namespace Beike\Repositories;

use Beike\Models\Order;

class OrderRepo
{
    /**
     * @param $data
     * @return Order
     * @throws \Throwable
     */
    public static function createOrder($data): Order
    {
        $order = new Order([

        ]);
        $order->saveOrFail();
        return $order;
    }
}
