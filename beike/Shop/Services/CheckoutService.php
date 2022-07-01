<?php
/**
 * CheckoutService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-30 19:37:05
 * @modified   2022-06-30 19:37:05
 */

namespace Beike\Shop\Services;

use Beike\Repositories\AddressRepo;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\CountryRepo;
use Beike\Shop\Http\Resources\Checkout\PaymentMethodItem;
use Beike\Shop\Http\Resources\Checkout\ShippingMethodItem;

class CheckoutService
{
    public static function checkoutData(): array
    {
        $customer = current_customer();

        $addresses = AddressRepo::listByCustomer(current_customer());
        $shipments = ShippingMethodItem::collection(PluginRepo::getShippingMethods())->jsonSerialize();
        $payments = PaymentMethodItem::collection(PluginRepo::getPaymentMethods())->jsonSerialize();

        $cartList = CartService::list($customer, true);
        $carts = CartService::reloadData($cartList);

        $data = [
            'current' => [
                'shipping_address_id' => 2,
                'payment_address_id' => 3,
                'shipping_method' => 'flat_shipping',
                'payment_method' => 'bk_stripe',
            ],
            'country_id' => (int)setting('country_id'),
            'customer_id' => $customer->id ?? null,
            'countries' => CountryRepo::all(),
            'addresses' => $addresses,
            'shipping_methods' => $shipments,
            'payment_methods' => $payments,
            'carts' => $carts
        ];
        return $data;
    }
}
