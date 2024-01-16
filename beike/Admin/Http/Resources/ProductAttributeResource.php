<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $this->load('attribute', 'attributeValue');
        $data = [
            'attribute'       => [
                'id'   => $this->attribute_id,
                'name' => $this->attribute->description->name,
            ],
            'attribute_value' => [
                'id'   => $this->attribute_value_id,
                'name' => $this->attributeValue->description->name,
            ],
        ];

        return $data;
    }
}
