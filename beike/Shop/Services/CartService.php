<?php
/**
 * CartService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2022-01-05 10:12:57
 * @modified   2022-01-05 10:12:57
 */

namespace Beike\Shop\Services;

use Exception;
use Beike\Models\Cart;
use Beike\Shop\Http\Resources\CartList;

class CartService
{
    /**
     * 获取购物车商品列表
     *
     * @param $customer
     * @param bool $selected
     * @return array
     */
    public static function list($customer, bool $selected = false): array
    {
        if (empty($customer)) {
            return [];
        }
        $cartBuilder = Cart::query()
            ->with(['sku.product.description'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('id');
        if ($selected) {
            $cartBuilder->where('selected', true);
        }
        $cartItems = $cartBuilder->get();
        $cartList = CartList::collection($cartItems)->jsonSerialize();
        return $cartList;
    }


    /**
     * 创建购物车或者更新购物车数量
     * @throws Exception
     */
    public static function add($sku, int $quantity, $customer = null)
    {
        $customerId = $customer->id ?? 0;
        $productId = $sku->product_id;
        $skuId = $sku->id;

        if (empty($sku) || $quantity == 0) {
            return null;
        }
        $cart = Cart::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->where('product_sku_id', $skuId)
            ->first();

        if ($cart) {
            $cart->selected = true;
            $cart->increment('quantity', $quantity);
        } else {
            $cart = Cart::query()->create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'product_sku_id' => $skuId,
                'quantity' => $quantity,
                'selected' => true,
            ]);
        }
        return $cart;
    }


    /**
     * 选择购物车商品
     *
     * @param $customer
     * @param $cartIds
     */
    public static function select($customer, $cartIds)
    {
        Cart::query()->where('customer_id', $customer->id)->update(['selected' => 0]);
        if (empty($cartIds)) {
            return;
        }
        Cart::query()->where('customer_id', $customer->id)
            ->whereIn('id', $cartIds)
            ->update(['selected' => 1]);
    }


    /**
     * 更新购物车数量
     */
    public static function updateQuantity($customer, $cartId, $quantity)
    {
        if (empty($cartId) || $quantity == 0) {
            return;
        }
        Cart::query()->where('customer_id', $customer->id)
            ->where('id', $cartId)
            ->update(['quantity' => $quantity, 'selected' => 1]);
    }


    public static function delete($customer, $cartId)
    {
        if (empty($cartId)) {
            return;
        }
        Cart::query()->where('customer_id', $customer->id)
            ->where('id', $cartId)
            ->delete();
    }


    /**
     * 获取购物车相关数据
     *
     * @param array $carts
     * @return array
     */
    public static function reloadData(array $carts = []): array
    {
        if (empty($carts)) {
            $carts = CartService::list(current_customer());
        }
        $selected = collect($carts)->where('selected', 1);
        $quantity = $selected->sum('quantity');
        $amount = $selected->sum('subtotal');

        $data = [
            'carts' => $carts,
            'quantity' => $quantity,
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];
        return $data;
    }
}
