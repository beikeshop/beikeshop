<?php
/**
 * OrderProductRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-04 21:14:12
 * @modified   2022-07-04 21:14:12
 */

namespace Beike\Repositories;

use Beike\Models\Order;

class OrderProductRepo
{
    public static function create(Order $order, $cartProducts)
    {
        $orderProducts = [];
        foreach ($cartProducts as $cartProduct) {
            $orderProducts[] = [
                'product_id' => $cartProduct['product_id'],
                'order_number' => $order->number,
                'product_sku' => $cartProduct['sku_id'],
                'name' => $cartProduct['name'],
                'image' => $cartProduct['image'],
                'quantity' => $cartProduct['quantity'],
                'price' => $cartProduct['price'],
            ];
        }
        $order->orderProducts()->createMany($orderProducts);
    }
}
