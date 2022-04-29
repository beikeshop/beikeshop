<?php

namespace App\Http\Resources\Admin;

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
            'image' => image_thumbnail($this->image),
            'name' => $this->description->name ?? '',
            'price_formatted' => $this->price_formatted,
            'active' => $this->active,
            'created_at' => (string)$this->created_at,
            'url_edit' => route('admin.products.edit', $this->id),
        ];

        return $data;
    }
}
