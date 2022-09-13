<?php
/**
 * OrderProductRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 21:14:12
 * @modified   2022-07-04 21:14:12
 */

namespace Beike\Repositories;

use Beike\Models\Order;
use Beike\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrderProductRepo
{
    /**
     * 创建商品明细
     *
     * @param Order $order
     * @param $cartProducts
     */
    public static function createOrderProducts(Order $order, $cartProducts)
    {
        $orderProducts = [];
        foreach ($cartProducts as $cartProduct) {
            $orderProducts[] = [
                'product_id' => $cartProduct['product_id'],
                'order_number' => $order->number,
                'product_sku' => $cartProduct['product_sku'],
                'name' => $cartProduct['name'],
                'image' => $cartProduct['image'],
                'quantity' => $cartProduct['quantity'],
                'price' => $cartProduct['price'],
            ];
        }
        $order->orderProducts()->createMany($orderProducts);
    }


    /**
     * 查找单条商品明细数据
     *
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id): Model|Collection|Builder|array|null
    {
        return OrderProduct::query()->findOrFail($id);
    }
}
