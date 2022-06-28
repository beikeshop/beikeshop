<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\ProductSku;
use Illuminate\Http\Request;
use Beike\Shop\Services\CartService;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $skuId = $request->sku_id;
        $quantity = $request->quantity ?? 1;
        $customer = current_customer();

        $sku = ProductSku::query()
            ->whereRelation('product', 'active', '=', true)
            ->findOrFail($skuId);

        $cart = CartService::add($sku, $quantity, $customer);

        return $cart;
    }

    public function miniCart()
    {
        $customer = current_customer();
        return CartService::list($customer);
    }
}
