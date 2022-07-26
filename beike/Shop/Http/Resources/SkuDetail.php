<?php
/**
 * SkuDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-20 11:33:06
 * @modified   2022-07-20 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkuDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'variants' => $this->variants,
            'position' => $this->position,
            'images' => array_map(function ($image) {
                return image_resize($image, 600, 600);
            }, $this->images ?? []),
            'model' => $this->model,
            'sku' => $this->sku,
            'price' => $this->price,
            'price_format' => currency_format($this->price),
            'origin_price' => $this->origin_price,
            'origin_price_format' => currency_format($this->origin_price),
            'quantity' => $this->quantity,
            'is_default' => $this->is_default,
        ];
    }
}
