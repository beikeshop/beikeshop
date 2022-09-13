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

use Beike\Repositories\RmaRepo;
use Illuminate\Http\Resources\Json\JsonResource;

class RmaDetail extends JsonResource
{
    public function toArray($request): array
    {
        $types = RmaRepo::getTypes();
        $statuses = RmaRepo::getStatuses();

        if ($this->reason) {
            $reason = json_decode($this->reason->name, true)[locale()] ?? '';
        } else {
            $reason = '';
        }
        return [
            'id' => $this->id,
            'order_product_id' => $this->order_product_id,
            'quantity' => $this->quantity,
            'opened' => $this->opened,
            'type' => $types[$this->type],
            'comment' => $this->comment,
            'status' => $statuses[$this->status],
            'created_at' => time_format($this->created_at),
            'email' => $this->email,
            'telephone' => $this->telephone,
            'product_name' => $this->product_name,
            'name' => $this->name,
            'sku' => $this->sku,
            'reason' => $reason,
            'type_text' => $this->type_text,
        ];
    }
}
