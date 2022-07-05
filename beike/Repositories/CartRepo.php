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
        if (empty($cart)) {
            $shippingMethod = PluginRepo::getShippingMethods()->first();
            $paymentMethod = PluginRepo::getPaymentMethods()->first();
            $defaultAddress = AddressRepo::listByCustomer($customer)->first();
            $cart = Cart::query()->create([
                'customer_id' => $customerId,
                'shipping_address_id' => $defaultAddress->id,
                'shipping_method_code' => $shippingMethod->code,
                'payment_address_id' => $defaultAddress->id,
                'payment_method_code' => $paymentMethod->code
            ]);
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
