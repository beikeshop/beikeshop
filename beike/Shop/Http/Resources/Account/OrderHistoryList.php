<?php
/**
 * OrderHistoryList.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-25 15:21:21
 * @modified   2023-08-25 15:21:21
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryList extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'order_id'      => $this->order_id,
            'status'        => $this->status,
            'status_format' => trans("order.{$this->status}"),
            'comment'       => $this->comment,
            'created_at'    => time_format($this->created_at),
            'updated_at'    => time_format($this->updated_at),
        ];
    }
}
