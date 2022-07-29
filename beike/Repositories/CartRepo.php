<?php
/**
 * CartRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-04 17:14:14
 * @modified   2022-07-04 17:14:14
 */

namespace Beike\Repositories;

use Beike\Models\Cart;
use Beike\Models\CartProduct;
use Beike\Models\Customer;

class CartRepo
{
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
                'shipping_method_code' => $shippingMethod->code,
                'payment_address_id' => $defaultAddressId,
                'payment_method_code' => $paymentMethod->code
            ]);
        } else {
            if ($cart->shipping_address_id == 0) {
                $cart->shipping_address_id = $defaultAddressId;
            }
            if ($cart->payment_address_id == 0) {
                $cart->payment_address_id = $defaultAddressId;
            }
            $cart->save();
        }
        return $cart;
    }

    public static function clearSelectedCartProducts($customer)
    {
        if (is_numeric($customer)) {
            $customer = Customer::query()->find($customer);
        }
        $customerId = $customer->id;
        Cart::query()->where('customer_id', $customerId)->delete();
        CartProduct::query()->where('customer_id', $customerId)->where('selected', true)->delete();
    }
}
