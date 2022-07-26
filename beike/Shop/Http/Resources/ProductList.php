<?php
/**
 * ProductList.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductList extends JsonResource
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
        return [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'url' => shop_route('products.show', ['product' => $this]),
            'price' => $this->price,
            'origin_price' => $this->origin_price,
            'image' => image_resize($this->image),
            'price_format' => currency_format($this->price),
            'category_id' => $this->category_id ?? null,
        ];
    }
}
