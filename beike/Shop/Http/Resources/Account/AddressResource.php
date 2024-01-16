<?php

namespace Beike\Shop\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->customer;
        $data     = [
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
            'default'    => ($customer->address_id == $this->id),
        ];

        return $data;
    }
}
