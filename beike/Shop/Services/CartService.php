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

use Beike\Models\CartProduct;
use Beike\Repositories\CartRepo;
use Beike\Shop\Http\Resources\CartDetail;
use Exception;

class CartService
{
    private static $cartList = null;

    /**
     * 获取购物车商品列表
     *
     * @param      $customer
     * @param bool $selected
     * @return array
     */
    public static function list($customer, bool $selected = false): array
    {
        if (self::$cartList !== null) {
            return self::$cartList;
        }

        $cartBuilder = CartRepo::allCartProductsBuilder($customer->id ?? 0);
        if ($selected) {
            $cartBuilder->where('selected', true);
        }
        $cartItems = $cartBuilder->get();

        $cartItems = $cartItems->filter(function ($item) {
            $description = $item->sku->product->description ?? '';
            $product     = $item->product                   ?? null;
            if (empty($description) || empty($product) || ! $product->active) {
                $item->delete();

                return false;
            }

            $cartQuantity = $item->quantity;
            $skuQuantity  = $item->sku->quantity;
            if ($cartQuantity > $skuQuantity && $skuQuantity > 0) {
                $item->quantity = $skuQuantity;
                $item->save();
            }
            $item->shipping = $product->shipping;

            return $description && $product;
        });

        $cartList = CartDetail::collection($cartItems)->jsonSerialize();

        return self::$cartList = hook_filter('service.cart.list', $cartList);
    }

    /**
     * 创建购物车或者更新购物车数量
     * @throws Exception
     */
    public static function add($sku, int $quantity, $customer = null)
    {
        $customerId = $customer->id ?? 0;

        if (empty($sku) || $quantity == 0) {
            return null;
        }

        $productId  = $sku->product_id;
        $skuCode    = $sku->sku;

        if ($customerId) {
            $builder = CartProduct::query()->where('customer_id', $customerId);
        } else {
            $builder = CartProduct::query()->where('session_id', get_session_id());
        }
        $cart = $builder->where('product_id', $productId)
            ->where('product_sku', $skuCode)
            ->first();

        if ($cart) {
            $cart->selected = true;
            $cart->increment('quantity', $quantity);
        } else {
            $cart = CartProduct::query()->create([
                'customer_id'    => $customerId,
                'session_id'     => get_session_id(),
                'product_id'     => $productId,
                'product_sku'    => $skuCode,
                'quantity'       => $quantity,
                'selected'       => true,
            ]);
        }

        $cartQuantity = $cart->quantity;
        $skuQuantity  = $cart->sku->quantity;
        if ($cartQuantity > $skuQuantity) {
            throw new Exception(trans('cart.stock_out'));
        }

        return $cart;
    }

    /**
     * 选择购物车商品
     *
     * @param      $customer
     * @param      $cartIds
     * @param bool $buyNow
     */
    public static function select($customer, $cartIds, bool $buyNow = false): void
    {
        if ($customer) {
            $builder = CartProduct::query()->where('customer_id', $customer->id);
        } else {
            $builder = CartProduct::query()->where('session_id', get_session_id());
        }
        if ($buyNow) {
            $builder->update(['selected' => 0]);
        }
        if (empty($cartIds)) {
            return;
        }
        $builder->whereIn('id', $cartIds)->update(['selected' => 1]);
    }

    /**
     * 反选购物车商品
     *
     * @param $customer
     * @param $cartIds
     */
    public static function unselect($customer, $cartIds): void
    {
        if (empty($cartIds)) {
            return;
        }
        if ($customer) {
            $builder = CartProduct::query()->where('customer_id', $customer->id);
        } else {
            $builder = CartProduct::query()->where('session_id', get_session_id());
        }
        $builder->whereIn('id', $cartIds)->update(['selected' => 0]);
    }

    /**
     * 更新购物车数量
     */
    public static function updateQuantity($customer, $cartId, $quantity): void
    {
        if (empty($cartId) || $quantity == 0) {
            return;
        }
        if ($customer) {
            $builder = CartProduct::query()->where('customer_id', $customer->id);
        } else {
            $builder = CartProduct::query()->where('session_id', get_session_id());
        }
        $builder->where('id', $cartId)
            ->update(['quantity' => $quantity, 'selected' => 1]);
    }

    /**
     * 删除购物车商品
     *
     * @param $customer
     * @param $cartId
     */
    public static function delete($customer, $cartId): void
    {
        if (empty($cartId)) {
            return;
        }
        $customerId = $customer->id ?? 0;
        if ($customerId) {
            $builder = CartProduct::query()->where('customer_id', $customerId);
        } else {
            $builder = CartProduct::query()->orWhere('session_id', get_session_id());
        }
        $builder->where('id', $cartId)
            ->delete();
    }

    /**
     * 获取购物车相关数据
     *
     * @param array $carts
     * @param null  $customer
     * @return array
     */
    public static function reloadData(array $carts = [], $customer = null): array
    {
        $customer = $customer ?: current_customer();
        if (empty($carts)) {
            $carts = self::list($customer);
        }

        $cartList = collect($carts)->where('selected', 1);

        $quantity    = $cartList->sum('quantity');
        $quantityAll = collect($carts)->sum('quantity');
        $amount      = $cartList->sum('subtotal');

        $data = [
            'carts'         => $carts,
            'quantity'      => $quantity,
            'quantity_all'  => $quantityAll,
            'amount'        => $amount,
            'amount_format' => currency_format($amount),
        ];

        return hook_filter('service.cart.data', $data);
    }

    public static function getAllQuantity(array $carts = [])
    {
        if (empty($carts)) {
            $carts = self::list(current_customer());
        }

        return collect($carts)->sum('quantity');
    }

    public static function getSelectedQuantity(array $carts = [])
    {
        if (empty($carts)) {
            $carts = self::list(current_customer(), true);
        }

        return collect($carts)->sum('quantity');
    }
}
