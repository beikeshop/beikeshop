<?php
/**
 * RmaService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2022-08-03 16:52:57
 * @modified   2022-08-03 16:52:57
 */

namespace Beike\Shop\Services;



use Beike\Repositories\OrderProductRepo;
use Beike\Repositories\RmaRepo;

class RmaService
{
    public static function createFromShop($data)
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
            'product_name' => $orderProduct->name,
            'quantity' => $data['quantity'],
            'opened' => $data['opened'],
            'rma_reason_id' => $data['rma_reason_id'],
            'type' => $data['type'],
            'comment' => $data['comment'],
            'status' => true,
        ];

        $rma = RmaRepo::create($params);

        return $rma;
    }
}
