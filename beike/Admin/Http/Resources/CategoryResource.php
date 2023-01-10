<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id'        => $this->id,
            'name'      => $this->description->name ?? '',
            'parent_id' => $this->parent_id,
            'position'  => $this->position,
            'active'    => $this->active,
            'url_edit'  => admin_route('categories.edit', $this),
            'children'  => self::collection($this->children),
        ];

        return $data;
    }
}
