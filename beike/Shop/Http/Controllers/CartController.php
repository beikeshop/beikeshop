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
        $data = [
            'data' => CartService::reloadData()
        ];
        return view("cart/cart", $data);
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

        $data = CartService::reloadData();
        return json_success(trans('common.updated_success'), $data);
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

        $data = CartService::reloadData();
        return json_success(trans('common.updated_success'), $data);
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

        $data = CartService::reloadData();
        return json_success(trans('common.deleted_success'), $data);
    }


    /**
     * 右上角购物车
     * @return mixed
     */
    public function miniCart()
    {
        $data = CartService::reloadData();
        return view('cart/mini', $data);
    }


    /**
     * 创建购物车
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $skuId = $request->sku_id;
        $quantity = $request->quantity ?? 1;
        $buyNow = (bool)$request->buy_now ?? false;
        $customer = current_customer();

        $sku = ProductSku::query()
            ->whereRelation('product', 'active', '=', true)
            ->findOrFail($skuId);

        $cart = CartService::add($sku, $quantity, $customer);
        if ($buyNow) {
            CartService::select($customer, [$cart->id]);
        }
        return json_success(trans('shop/carts.added_to_cart'), $cart);
    }
}
