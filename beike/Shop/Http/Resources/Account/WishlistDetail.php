<?php
/**
 * WishlistDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
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
        $product     = $this->product;
        $masterSku   = $product->masterSku;
        $image       = $this->product->image ?: $masterSku->image;
        $productName = $product->description->name ?? '';

        $data = [
            'id'           => $this->id,
            'product_id'   => $this->product_id,
            'image'        => image_resize($image),
            'product_name' => sub_string($productName, 24),
            'price'        => currency_format($masterSku->price),
        ];

        return $data;
    }
}
