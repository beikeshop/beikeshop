<?php
/**
 * OrderSimpleList.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-05 10:39:55
 * @modified   2022-07-05 10:39:55
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSimpleList extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'            => $this->id,
            'number'        => $this->number,
            'status_format' => $this->status_format,
            'next_status'   => $this->getNextStatuses(),
            'status'        => $this->status,
        ];

        return $data;
    }
}
