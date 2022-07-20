<?php
/**
 * ProductDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'description' => $this->description->description ?? '',
            'image' => image_resize($this->image),
            'category_id' => $this->category_id ?? null,
            'variables' => $this->variables,
            'skus' => SkuDetail::collection($this->skus)->jsonSerialize(),
        ];
    }
}
