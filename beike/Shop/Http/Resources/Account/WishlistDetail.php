<?php
/**
 * WishlistDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-25 20:39:55
 * @modified   2022-07-25 20:39:55
 */

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistDetail extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'image' => image_resize($this->product->image, 100, 100),
            'product_name' => $this->product->description->name,
            'price' => currency_format($this->product->price)
        ];

        return $data;
    }
}
