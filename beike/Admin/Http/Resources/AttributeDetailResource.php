<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeDetailResource extends JsonResource
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
            'id'                   => $this->id,
            'attribute_group_id'   => $this->attribute_group_id,
            'name'                 => $this->description->name ?? '',
            'sort_order'           => $this->sort_order,
            'attribute_group_name' => $this->attributeGroup->description->name ?? '',
            'created_at'           => time_format($this->created_at),
            'values'               => AttributeValueResource::collection($this->values),
            'descriptions'         => $this->descriptions,
        ];

        return $data;
    }
}
