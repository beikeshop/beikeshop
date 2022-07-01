<?php

namespace Beike\Shop\Http\Resources\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $pluginSetting = $this->plugin;
        return [
            'type' => $this->type,
            'code' => $this->code,
            'name' => $pluginSetting->name,
            'description' => $pluginSetting->description,
            'icon' => $pluginSetting->icon,
        ];
    }
}
