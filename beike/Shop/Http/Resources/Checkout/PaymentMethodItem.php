<?php

namespace Beike\Shop\Http\Resources\Checkout;

use Beike\Admin\Http\Resources\PluginResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodItem extends JsonResource
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
        $pluginResource = (new PluginResource($this->plugin))->jsonSerialize();

        return [
            'type'        => $this->type,
            'code'        => $this->code,
            'name'        => $pluginResource['name'],
            'description' => $pluginResource['description'],
            'icon'        => $pluginResource['icon'],
        ];
    }
}
