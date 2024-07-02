<?php
namespace Plugin\Upsell\Controllers;

use Beike\Models\ProductSku;
use Beike\Shop\Http\Requests\CartRequest;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CartController extends Controller
{
    /**
     * POST /carts {sku_id:1, quantity: 2}
     * 创建购物车
     *
     * @param CartRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function store(CartRequest $request)
    {
        try {
            $skuId = $request->sku_id;
            $quantity = $request->quantity ?? 1;
            $buyNow = (bool) $request->buy_now ?? false;
            $from = $request->from ?? "";
            $customer = current_customer();

            $sku = ProductSku::query()
                ->whereRelation('product', 'active', '=', true)
                ->findOrFail($skuId);

            $cart = CartService::add($sku, $quantity, $customer);

            // 额外处理
            $cart['from'] = $from;

            if ($buyNow) {
                CartService::select($customer, [$cart->id], true);
            }

            $cart = hook_filter('cart.store.data', $cart);

            return json_success(trans('shop/carts.added_to_cart'), $cart);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
