<?php
/**
 * OrderProductList.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-14 18:41:19
 * @modified   2023-08-14 18:41:19
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductSimple extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'            => $this->id,
            'name'          => $this->name,
            'quantity'      => $this->quantity,
            'price'         => currency_format($this->price),
            'image'         => image_resize($this->image),
        ];

        return $data;
    }
}
