<?php

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class CartList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sku = $this->sku;
        $price = $sku->price;
        $description = $sku->product->description;
        $subTotal = $price * $this->quantity;
        return [
            'product_id' => $this->product_id,
            'sku_id' => $this->product_sku_id,
            'name' => $description->name,
            'image' => image_resize($sku->image),
            'quantity' => $this->quantity,
            'selected' => $this->selected,
            'price' => $price,
            'price_format' => currency_format($price),
            'subtotal' => $subTotal,
            'subtotal_format' => currency_format($subTotal),
        ];
    }
}
