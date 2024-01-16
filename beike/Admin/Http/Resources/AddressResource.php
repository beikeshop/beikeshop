<?php

namespace Beike\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id'         => $this->id,
            'name'       => $this->name,
            'phone'      => $this->phone,
            'country_id' => $this->country_id,
            'country'    => $this->country->name,
            'zone_id'    => $this->zone_id,
            'zone'       => $this->zone,
            'city'       => $this->city,
            'zipcode'    => $this->zipcode,
            'address_1'  => $this->address_1,
            'address_2'  => $this->address_2,
        ];

        return $data;
    }
}
