<?php
/**
 * CartRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 17:14:14
 * @modified   2022-07-04 17:14:14
 */

namespace Beike\Repositories;

use Beike\Models\Cart;
use Beike\Models\Customer;
use Beike\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartRepo
{
    /**
     * 创建购物车
     *
     * @param $customer
     * @return Builder|Model|object|null
     */
    public static function createCart($customer)
    {
        if (is_numeric($customer)) {
            $customer = Customer::query()->find($customer);
        }
        $customerId = $customer->id;
        $cart = Cart::query()->where('customer_id', $customerId)->first();
        $defaultAddress = AddressRepo::listByCustomer($customer)->first();
        $defaultAddressId = $defaultAddress->id ?? 0;

        if (empty($cart)) {
            $shippingMethod = PluginRepo::getShippingMethods()->first();
            $paymentMethod = PluginRepo::getPaymentMethods()->first();
            $cart = Cart::query()->create([
                'customer_id' => $customerId,
                'shipping_address_id' => $defaultAddressId,
                'shipping_method_code' => $shippingMethod->code ?? '',
                'payment_address_id' => $defaultAddressId,
                'payment_method_code' => $paymentMethod->code ?? ''
            ]);
        } else {
            if ($cart->shipping_address_id == 0 || empty(AddressRepo::find($cart->shipping_address_id))) {
                $cart->shipping_address_id = $defaultAddressId;
            }
            if ($cart->payment_address_id == 0 || empty(AddressRepo::find($cart->payment_address_id))) {
                $cart->payment_address_id = $defaultAddressId;
            }
            $cart->save();
        }
        $cart->loadMissing(['shippingAddress', 'paymentAddress']);
        return $cart;
    }


    /**
     * 清空购物车以及购物车已选中商品
     *
     * @param $customer
     */
    public static function clearSelectedCartProducts($customer)
    {
        if (is_numeric($customer)) {
            $customer = Customer::query()->find($customer);
        }
        $customerId = $customer->id;
        Cart::query()->where('customer_id', $customerId)->delete();
        self::selectedCartProductsBuilder($customerId)->delete();
    }


    /**
     * 获取已选中购物车商品列表
     *
     * @param $customerId
     * @return Builder[]|Collection
     */
    public static function selectedCartProducts($customerId)
    {
        return self::selectedCartProductsBuilder($customerId)->get();
    }


    /**
     * 已选中购物车商品 builder
     *
     * @param $customerId
     * @return Builder
     */
    public static function selectedCartProductsBuilder($customerId): Builder
    {
        return self::allCartProductsBuilder($customerId)->where('selected', true);
    }


    /**
     * 当前购物车所有商品 builder
     *
     * @param $customerId
     * @return Builder
     */
    public static function allCartProductsBuilder($customerId): Builder
    {
        return CartProduct::query()
            ->with(['product.description', 'sku.product.description'])
            ->where('customer_id', $customerId)
            ->orderByDesc('id');
    }
}
