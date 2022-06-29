<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Beike\Shop\Services\CartService;

class CartController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $data = CartService::reloadData();
        return view("cart", $data);
    }

    /**
     * 选中购物车商品
     *
     * POST /carts/select {cart_ids:[1, 2]}
     * @param Request $request
     * @return array
     */
    public function select(Request $request): array
    {
        $cartIds = $request->get('cart_ids');
        $customer = current_customer();
        CartService::select($customer, $cartIds);

        return CartService::reloadData();
    }

    /**
     * PUT /carts/{cart_id} {quantity: 123}
     * @param Request $request
     * @param $cartId
     * @return array
     */
    public function update(Request $request, $cartId): array
    {
        $customer = current_customer();
        $quantity = (int)$request->get('quantity');
        CartService::updateQuantity($customer, $cartId, $quantity);

        return CartService::reloadData();
    }


    /**
     * DELETE /carts/{cart_id}
     * @param Request $request
     * @param $cartId
     * @return array
     */
    public function destroy(Request $request, $cartId): array
    {
        $customer = current_customer();
        CartService::delete($customer, $cartId);

        return CartService::reloadData();
    }


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
