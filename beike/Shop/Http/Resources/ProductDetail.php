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
            'variables' => $this->decodeVariables($this->variables),
            'skus' => SkuDetail::collection($this->skus)->jsonSerialize(),
        ];
    }

    private function decodeVariables($variables)
    {
        $lang = current_language_code();
        return array_map(function ($item) use ($lang){
            return [
                'name' => $item['name'][$lang],
                'values' => array_map(function ($item) use ($lang){
                    return [
                        'name' => $item['name'][$lang],
                        'image' => image_resize($item['image'], 100, 100),
                    ];
                }, $item['values']),
            ];
        }, $variables);
    }
}
