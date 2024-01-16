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
            'product_id'    => $this->product_id,
            'name'          => $this->name,
            'sku'           => $this->product_sku,
            'quantity'      => $this->quantity,
            'price'         => currency_format($this->price),
            'total'         => $this->price * $this->quantity,
            'total_format'  => currency_format($this->price * $this->quantity),
            'image'         => image_resize($this->image),
        ];

        return $data;
    }
}
