<?php
/**
 * ProductDetail.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $data = [
            'id'               => $this->id,
            'name'             => $this->description->name             ?? '',
            'description'      => $this->description->content          ?? '',
            'meta_title'       => $this->description->meta_title       ?? '',
            'meta_keywords'    => $this->description->meta_keywords    ?? '',
            'meta_description' => $this->description->meta_description ?? '',
            'brand_id'         => $this->brand->id                     ?? 0,
            'brand_name'       => $this->brand->name                   ?? '',
            'video'            => $this->video                         ?? '',
            'weight'           => $this->weight                        ?? '',
            'weight_class'     => $this->weight_class                  ?? '',
            'images'           => array_map(function ($image) {
                return [
                    'preview' => image_resize($image, 600, calculate_height_by_ratio(600)),
                    'popup'   => image_resize($image, 800, calculate_height_by_ratio(800)),
                    'thumb'   => image_resize($image, 150, calculate_height_by_ratio(150)),
                ];
            }, $this->images ?? []),
            'attributes'       => $this->formatAttributes(),
            'variables'        => $this->decodeVariables($this->variables),
            'skus'             => SkuDetail::collection($this->skus)->jsonSerialize(),
            'in_wishlist'      => $this->inCurrentWishlist->id ?? 0,
            'active'           => (bool) $this->active,
        ];

        return hook_filter('resource.product.detail', $data);
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
                'name'   => $item['name'][$lang] ?? '',
                'values' => array_map(function ($item) use ($lang) {
                    return [
                        'name'  => $item['name'][$lang] ?? '',
                        'image' => $item['image'] ? image_resize($item['image']) : '',
                    ];
                }, $item['values']),
            ];
        }, $variables);
    }

    /**
     * 格式化属性数据
     *
     * @return array
     */
    private function formatAttributes(): array
    {
        return $this->attributes
            ->sortBy(fn($a) => $a->attribute->attributeGroup->sort_order) // 对属性组排序
            ->groupBy(fn($item) => $item->attribute->attribute_group_id) // 按属性组分组
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'attribute_group_name' => $first->attribute->attributeGroup->description->name,
                    'attributes' => $items
                        ->sortBy(fn($a) => $a->attribute->sort_order) // 对组内属性排序
                        ->map(fn($a) => [
                            'attribute'       => $a->attribute->description->name,
                            'attribute_value' => $a->attributeValue->description->name,
                        ])
                        ->values()
                        ->all(),
                ];
            })
            ->toArray();
    }
}
