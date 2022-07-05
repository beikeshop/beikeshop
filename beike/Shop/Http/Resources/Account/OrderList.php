<?php
/**
 * OrderList.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-05 10:39:55
 * @modified   2022-07-05 10:39:55
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderList extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'number' => $this->number,
        ];

        return $data;
    }
}
