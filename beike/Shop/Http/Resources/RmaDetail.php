<?php
/**
 * BrandDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-03 10:33:06
 * @modified   2022-08-03 10:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RmaDetail extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->reason) {
            $reason = json_decode($this->reason->name, true)[locale()] ?? '';
        } else {
            $reason = '';
        }

        return [
            'id'               => $this->id,
            'order_id'         => $this->order_id,
            'order_product_id' => $this->order_product_id,
            'quantity'         => $this->quantity,
            'opened'           => $this->opened,
            'type'             => $this->type,
            'type_format'      => $this->type_format,
            'comment'          => $this->comment,
            'status'           => $this->status,
            'status_format'    => $this->status_format,
            'created_at'       => time_format($this->created_at),
            'email'            => $this->email,
            'telephone'        => $this->telephone,
            'product_name'     => $this->product_name,
            'name'             => $this->name,
            'sku'              => $this->sku,
            'images'           => $this->images,
            'reason'           => $reason,
        ];
    }
}
