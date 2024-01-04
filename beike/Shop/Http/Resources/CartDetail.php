<?php

namespace Beike\Shop\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     * @throws \Exception
     */
    public function toArray($request)
    {
        $sku         = $this->sku;
        $product     = $sku->product;
        $price       = $sku->price;
        $skuCode     = $sku->sku;
        $description = $product->description;
        $productName = $description->name;
        $subTotal    = $price * $this->quantity;
        $image       = $sku->image ?: $product->image;

        $result = [
            'cart_id'         => $this->id,
            'product_id'      => $this->product_id,
            'sku_id'          => $sku->id,
            'sku'             => $this->product_sku,
            'product_sku'     => $skuCode,
            'name'            => $productName,
            'name_format'     => sub_string($productName),
            'image'           => $image,
            'image_url'       => image_resize($image),
            'quantity'        => $this->quantity,
            'selected'        => $this->selected,
            'price'           => $price,
            'shipping'        => $this->shipping,
            'price_format'    => currency_format($price),
            'tax_class_id'    => $product->tax_class_id,
            'subtotal'        => $subTotal,
            'subtotal_format' => currency_format($subTotal),
            'variant_labels'  => trim($sku->getVariantLabel()),
        ];

        return hook_filter('resource.cart.detail', $result);
    }
}
