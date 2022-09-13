<?php
/**
 * RmaDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-31 11:56:28
 * @modified   2022-08-31 11:56:28
 */

namespace Beike\Admin\Http\Resources;

use Beike\Repositories\RmaRepo;
use Illuminate\Http\Resources\Json\JsonResource;

class RmaDetail extends JsonResource
{
    public function toArray($request): array
    {
        $types = RmaRepo::getTypes();
        $statuses = RmaRepo::getStatuses();

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
            'reason' => $this->reason ? (json_decode($this->reason->name, true)[locale()] ?? '') : '',
            'type_text' => $this->type_text,
        ];
    }
}
