<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ProductSku;
use App\Services\CartService;
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
