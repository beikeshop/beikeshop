<?php

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'email'               => $this->email,
            'status'              => $this->status ? trans('common.enable') : trans('common.disable'),
            'avatar'              => image_resize($this->avatar),
            'from'                => $this->from,
            'customer_group_name' => $this->customer_group_name,
        ];
    }
}
