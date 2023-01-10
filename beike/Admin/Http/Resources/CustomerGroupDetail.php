<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerGroupDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id'                  => $this->id,
            'total'               => $this->total,
            'reward_point_factor' => $this->reward_point_factor,
            'use_point_factor'    => $this->use_point_factor,
            'discount_factor'     => $this->discount_factor,
            'level'               => $this->level,
            'name'                => $this->description->name        ?? '',
            'description'         => $this->description->description ?? '',
        ];

        return $data;
    }
}
