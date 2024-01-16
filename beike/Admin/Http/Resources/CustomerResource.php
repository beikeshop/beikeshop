<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function toArray($request)
    {
        $data = [
            'id'                  => $this->id,
            'name'                => $this->name,
            'email'               => $this->email,
            'status'              => $this->status,
            'created_at'          => time_format($this->created_at),
            'avatar'              => image_resize($this->avatar),
            'from'                => $this->from,
            'customer_group_name' => $this->customerGroup->description->name ?? '',
            'edit'                => admin_route('customers.edit', $this->id),
            'delete'              => admin_route('customers.destroy', $this->id),
        ];

        $params = [
            'object' => $this,
            'data'   => $data,
        ];

        return hook_filter('resource.customer', $params)['data'];
    }
}
