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
     * @throws \Exception
     */
    public function toArray($request): array
    {
        $pluginSetting = $this->plugin;

        return [
            'type'        => $this->type,
            'code'        => $this->code,
            'name'        => $pluginSetting->name,
            'description' => $pluginSetting->description,
            'icon'        => plugin_resize($this->code, $pluginSetting->icon),
        ];
    }
}
