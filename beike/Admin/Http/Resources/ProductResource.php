<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $masterSku = $this->master_sku;

        $data = [
            'id' => $this->id,
            'images' => array_map(function ($image) {
                return thumbnail($image);
            }, $this->images ?? []),
            'name' => $this->description->name ?? '',
            'price_formatted' => currency_format($masterSku->price),
            'active' => $this->active,
            'position' => $this->position,
            'url' => shop_route('products.show', $this->id),
            'created_at' => time_format($this->created_at),
            'deleted_at' => $this->deleted_at ? time_format($this->deleted_at) : '',
            'url_edit' => admin_route('products.edit', $this->id),
        ];

        return $data;
    }
}
