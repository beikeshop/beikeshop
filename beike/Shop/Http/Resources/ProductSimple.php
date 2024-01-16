<?php
/**
 * ProductSimple.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSimple extends JsonResource
{
    /**
     * 图片列表页Item
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $masterSku = $this->masterSku;
        if (empty($masterSku)) {
            throw new \Exception("invalid master sku for product {$this->id}");
        }

        $name   = $this->description->name ?? '';
        $images = $this->images;

        $data = [
            'id'                  => $this->id,
            'sku_id'              => $masterSku->id,
            'name'                => $name,
            'name_format'         => $name,
            'url'                 => $this->url,
            'price'               => $masterSku->price,
            'origin_price'        => $masterSku->origin_price,
            'price_format'        => currency_format($masterSku->price),
            'origin_price_format' => currency_format($masterSku->origin_price),
            'category_id'         => $this->category_id           ?? null,
            'in_wishlist'         => $this->inCurrentWishlist->id ?? 0,

            'images'              => array_map(function ($item) {
                return image_resize($item, 400, 400);
            }, $images),
        ];

        return hook_filter('resource.product.simple', $data);
    }
}
