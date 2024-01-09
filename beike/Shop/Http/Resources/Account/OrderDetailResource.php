<?php
/**
 * OrderDetailResource.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-16 16:24:56
 * @modified   2023-08-16 16:24:56
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'                     => $this->id,
            'number'                 => $this->number,
            'status_format'          => $this->status_format,
            'next_status'            => $this->getNextStatuses(),
            'status'                 => $this->status,
            'total'                  => $this->total,
            'total_format'           => $this->total_format,
            'comment'                => $this->comment,
            'shipping_method_code'   => $this->shipping_method_code,
            'shipping_method_name'   => $this->shipping_method_name,
            'shipping_customer_name' => $this->shipping_customer_name,
            'shipping_calling_code'  => $this->shipping_calling_code,
            'shipping_telephone'     => $this->shipping_telephone,
            'shipping_country'       => $this->shipping_country,
            'shipping_zone'          => $this->shipping_zone,
            'shipping_city'          => $this->shipping_city,
            'shipping_address_1'     => $this->shipping_address_1,
            'shipping_address_2'     => $this->shipping_address_2,
            'shipping_zipcode'       => $this->shipping_zipcode,
            'payment_method_code'    => $this->payment_method_code,
            'payment_method_name'    => $this->payment_method_name,
            'payment_customer_name'  => $this->payment_customer_name,
            'payment_calling_code'   => $this->payment_calling_code,
            'payment_telephone'      => $this->payment_telephone,
            'payment_country'        => $this->payment_country,
            'payment_zone'           => $this->payment_zone,
            'payment_city'           => $this->payment_city,
            'payment_address_1'      => $this->payment_address_1,
            'payment_address_2'      => $this->payment_address_2,
            'payment_zipcode'        => $this->payment_zipcode,
            'created_at'             => time_format($this->created_at),
            'order_products'         => OrderProductSimple::collection($this->orderProducts),
            'order_totals'           => $this->orderTotals,
            'order_histories'        => OrderHistoryList::collection($this->orderHistories),
            'order_shipments'        => $this->orderShipments,
        ];

        return $data;
    }
}
