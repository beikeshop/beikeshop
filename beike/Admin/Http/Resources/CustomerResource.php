<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status ? '启用' : '禁用',
            'avatar' => image_resize($this->avatar),
            'from' => $this->from,
            'customer_group_name' => $this->customer_group_name ?? $this->group->description->name,
            'edit' => admin_route('customers.edit', $this->id),
            'delete' => admin_route('customers.destroy', $this->id),
        ];

        return $data;
    }
}
