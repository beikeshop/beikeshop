<?php
/**
 * CartController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-06-21 10:36:56
 * @modified   2023-06-21 10:36:56
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\ProductSku;
use Beike\Shop\Http\Requests\CartRequest;
use Beike\Shop\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): array
    {
        return CartService::reloadData();
    }

    public function store(CartRequest $request)
    {
        try {
            $skuId    = $request->get('sku_id');
            $quantity = $request->get('quantity', 1);
            $buyNow   = (bool) $request->get('buy_now', false);
            $customer = current_customer();

            $sku = ProductSku::query()
                ->whereRelation('product', 'active', '=', true)
                ->findOrFail($skuId);

            $cart = CartService::add($sku, $quantity, $customer);
            if ($buyNow) {
                CartService::select($customer, [$cart->id]);
            }

            $cart = hook_filter('cart.store.data', $cart);

            return json_success(trans('shop/carts.added_to_cart'), $cart);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function update(CartRequest $request, $cartId)
    {
        try {
            $customer = current_customer();
            $quantity = (int) $request->get('quantity');
            CartService::updateQuantity($customer, $cartId, $quantity);

            $data = CartService::reloadData();

            $data = hook_filter('cart.update.data', $data);

            return json_success(trans('common.updated_success'), $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function destroy(Request $request, $cartId)
    {
        try {
            $customer = current_customer();
            CartService::delete($customer, $cartId);

            $data = CartService::reloadData();

            $data = hook_filter('cart.destroy.data', $data);

            return json_success(trans('common.deleted_success'), $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
