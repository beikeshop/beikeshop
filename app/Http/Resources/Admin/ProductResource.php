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
            'name' => $this->translation->name ?? '',
            'active' => $this->active,
            'url_edit' => route('admin.products.edit', $this->id),
        ];

        return $data;
    }
}
