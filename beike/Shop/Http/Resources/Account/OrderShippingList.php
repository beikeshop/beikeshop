<?php
/**
 * OrderShippingList.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2023-11-28 10:39:55
 * @modified   2023-11-28 10:39:55
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderShippingList extends JsonResource
{
    public function toArray($request): array
    {
        $products = OrderProductSimple::collection($this->orderProducts)->jsonSerialize();
        $data     = [
            'store_name'              => system_setting('base.meta_title'),
            'id'                      => $this->id,
            'number'                  => $this->number,
            'customer_name'           => $this->customer_name,
            'email'                   => $this->email,
            'total'                   => currency_format($this->total),
            'product_total'           => currency_format(array_sum(array_column($products, 'total'))),
            'shipping_customer_name'  => $this->shipping_customer_name,
            'shipping_calling_code'   => $this->shipping_calling_code,
            'shipping_telephone'      => $this->shipping_telephone,
            'shipping_country'        => $this->shipping_country,
            'shipping_zone'           => $this->shipping_zone,
            'shipping_city'           => $this->shipping_city,
            'shipping_address_1'      => $this->shipping_address_1,
            'shipping_address_2'      => $this->shipping_address_2,
            'website'                 => shop_route('home.index'),
            'order_products'          => $products,
            'created_at'              => $this->created_at,
            'status_format'           => $this->status_format,
            'status'                  => $this->status,
        ];

        return $data;
    }
}
