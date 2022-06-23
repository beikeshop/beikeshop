<?php

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $item = [
            'id' => $this->id,
            'name' => $this->description->name ?? '',

        ];
        if ($this->children->count() > 0) {
            $item['children'] = self::collection($this->children);
        }
        return $item;
    }
}
