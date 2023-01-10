<?php
/**
 * OrderSimple.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-24 10:51:53
 * @modified   2022-08-24 10:51:53
 */

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSimple extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'            => $this->id,
            'number'        => $this->number,
            'customer_name' => $this->customer_name,
            'email'         => $this->email,
            'telephone'     => $this->telephone,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'status_format' => $this->status_format,
        ];

        return $data;
    }
}
