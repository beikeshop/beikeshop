<?php

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $item = [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'url' => shop_route('categories.show', ['category' => $this])
        ];

        if ($this->relationLoaded('children') && $this->children->count() > 0) {
            $item['children'] = self::collection($this->children);
        } else {
            $item['children'] = [];
        }
        return $item;
    }
}
