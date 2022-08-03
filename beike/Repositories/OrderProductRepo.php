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
use Beike\Models\OrderProduct;
use Beike\Models\Rma;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id)
    {
        return OrderProduct::query()->findOrFail($id);
    }
}
