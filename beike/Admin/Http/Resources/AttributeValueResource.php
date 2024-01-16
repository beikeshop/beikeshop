<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
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
            'id'           => $this->id,
            'attribute_id' => $this->attribute_id,
            'name'         => $this->description->name,
            'description'  => $this->description,
            'descriptions' => $this->descriptions,
            'created_at'   => time_format($this->created_at),
        ];

        return $data;
    }
}
