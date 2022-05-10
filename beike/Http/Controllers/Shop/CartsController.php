<?php

namespace Beike\Http\Controllers\Shop;

use Beike\Models\ProductSku;
use Beike\Services\CartService;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function store(Request $request)
    {
        $skuId = $request->sku_id;
        $quantity = $request->quantity ?? 1;

        $sku = ProductSku::query()
            ->whereRelation('product', 'active', '=', true)
            ->findOrFail($skuId);

        $cart = (new CartService)->add($sku, $quantity);

        return $cart;
    }
}
