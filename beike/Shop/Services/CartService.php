<?php
/**
 * CartService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-01-05 10:12:57
 * @modified   2022-01-05 10:12:57
 */

namespace Beike\Shop\Services;

use Exception;
use Beike\Models\CartProduct;
use Beike\Repositories\CartRepo;
use Beike\Shop\Http\Resources\CartDetail;

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
        $cartBuilder = CartRepo::allCartProductsBuilder($customer->id);
        if ($selected) {
            $cartBuilder->where('selected', true);
        }
        $cartItems = $cartBuilder->get();

        $cartItems = $cartItems->filter(function ($item) {
            $description = $item->sku->product->description ?? '';
            $product = $item->product ?? null;
            if (empty($description) || empty($product)) {
                $item->delete();
            }
            return $description && $product;
        });

        return CartDetail::collection($cartItems)->jsonSerialize();
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
        $cart = CartProduct::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->where('product_sku_id', $skuId)
            ->first();

        if ($cart) {
            $cart->selected = true;
            $cart->increment('quantity', $quantity);
        } else {
            $cart = CartProduct::query()->create([
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
        CartProduct::query()->where('customer_id', $customer->id)->update(['selected' => 0]);
        if (empty($cartIds)) {
            return;
        }
        CartProduct::query()->where('customer_id', $customer->id)
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
        CartProduct::query()->where('customer_id', $customer->id)
            ->where('id', $cartId)
            ->update(['quantity' => $quantity, 'selected' => 1]);
    }


    /**
     * 删除购物车商品
     *
     * @param $customer
     * @param $cartId
     */
    public static function delete($customer, $cartId)
    {
        if (empty($cartId)) {
            return;
        }
        CartProduct::query()->where('customer_id', $customer->id)
            ->where('id', $cartId)
            ->delete();
    }


    /**
     * 获取购物车相关数据
     *
     * @param array $carts
     * @param bool $showAll
     * @return array
     */
    public static function reloadData(array $carts = [], bool $showAll = false): array
    {
        if (empty($carts)) {
            $carts = CartService::list(current_customer());
        }

        $cartList = collect($carts);
        if (!$showAll) {
            $cartList = collect($carts)->where('selected', 1);
        }
        $quantity = $cartList->sum('quantity');
        $amount = $cartList->sum('subtotal');

        $data = [
            'carts' => $carts,
            'quantity' => $quantity,
            'amount' => $amount,
            'amount_format' => currency_format($amount),
        ];
        return hook_filter('cart.data', $data);
    }
}
