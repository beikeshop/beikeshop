<?php
/**
 * ProductDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Beike\Repositories\CustomerRepo;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'description' => $this->description->content ?? '',
            'images' => array_map(function ($image) {
                return [
                    'preview' => image_resize($image, 500, 500),
                    'popup' => image_resize($image, 800, 800),
                    'thumb' => image_resize($image, 150, 150)
                ];
            }, $this->images ?? []),
            'category_id' => $this->category_id ?? null,
            'variables' => $this->decodeVariables($this->variables),
            'skus' => SkuDetail::collection($this->skus)->jsonSerialize(),
            'in_wishlist' => $this->inCurrentWishlist->id ?? 0,
            'active' => (bool)$this->active,
        ];
    }


    /**
     * 处理多规格商品数据
     *
     * @param $variables
     * @return array|array[]
     * @throws \Exception
     */
    private function decodeVariables($variables): array
    {
        $lang = locale();
        if (empty($variables)) {
            return [];
        }
        return array_map(function ($item) use ($lang) {
            return [
                'name' => $item['name'][$lang] ?? '',
                'values' => array_map(function ($item) use ($lang) {
                    return [
                        'name' => $item['name'][$lang] ?? '',
                        'image' => $item['image'] ? image_resize($item['image']) : '',
                    ];
                }, $item['values']),
            ];
        }, $variables);
    }
}
