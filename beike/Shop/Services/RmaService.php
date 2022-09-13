<?php
/**
 * RmaService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-03 16:52:57
 * @modified   2022-08-03 16:52:57
 */

namespace Beike\Shop\Services;

use Beike\Repositories\RmaRepo;
use Illuminate\Database\Eloquent\Model;
use Beike\Repositories\OrderProductRepo;
use Illuminate\Database\Eloquent\Builder;

class RmaService
{
    /**
     * 创建RMA
     *
     * @param $data
     * @return Model|Builder
     */
    public static function createFromShop($data): Model|Builder
    {
        $orderProduct = OrderProductRepo::find($data['order_product_id']);
        $customer = current_customer();
        $params = [
            'order_id' => $orderProduct->order->id,
            'order_product_id' => $data['order_product_id'],
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'telephone' => $customer->telephone ?? '',
            'product_name' => $orderProduct->name,
            'sku' => $orderProduct->product_sku,
            'quantity' => $data['quantity'],
            'opened' => $data['opened'],
            'rma_reason_id' => $data['rma_reason_id'],
            'type' => $data['type'],
            'comment' => $data['comment'],
            'status' => 'pending',
        ];

        return RmaRepo::create($params);
    }
}
