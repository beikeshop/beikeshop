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

use Illuminate\Http\Resources\Json\JsonResource;

class ProductList extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'url' => shop_route('products.show', ['product' => $this]),
            'price' => $this->price,
            'image' => image_resize($this->image),
            'price_format' => currency_format($this->price),
            'category_id' => $this->category_id ?? null,
        ];
    }
}
