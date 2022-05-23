<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'image' => thumbnail($this->image),
            'name' => $this->description->name ?? '',
            'price_formatted' => $this->price_formatted,
            'active' => $this->active,
            'position' => $this->position,
            'created_at' => (string)$this->created_at,
            'deleted_at' => (string)$this->deleted_at,
            'url_edit' => route('admin.products.edit', $this->id),
        ];

        return $data;
    }
}
