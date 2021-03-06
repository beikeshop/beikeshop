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

        $data = CartService::reloadData();
        return json_success('获取成功', $data);
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
        return json_success('获取成功', $data);
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
        return json_success('获取成功', $data);
    }


    /**
     * 右上角购物车
     * @return array
     */
    public function miniCart(): array
    {
        $data = CartService::reloadData();
        return json_success('获取成功', $data);
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
        $customer = current_customer();

        $sku = ProductSku::query()
            ->whereRelation('product', 'active', '=', true)
            ->findOrFail($skuId);

        $cart = CartService::add($sku, $quantity, $customer);
        return json_success('获取成功', $cart);
    }
}
