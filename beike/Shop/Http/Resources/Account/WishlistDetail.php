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
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $product = $this->product;
        $masterSku = $product->master_sku;
        $image = $this->product->image ?: $masterSku->image;

        $data = [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'image' => image_resize($image),
            'product_name' => $product->description->name,
            'price' => currency_format($masterSku->price)
        ];

        return $data;
    }
}
