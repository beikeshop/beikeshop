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
use Beike\Repositories\SettingRepo;

class CheckoutService
{
    public static function checkoutData(): array
    {
        $customer = current_customer();

        $addresses = AddressRepo::listByCustomer(current_customer());
        $shipments = SettingRepo::getShipments();
        $payments = SettingRepo::getPayments();

        $cartList = CartService::list($customer, true);
        $carts = CartService::reloadData($cartList);

        $data = [
            'addresses' => $addresses,
            'shipments' => $shipments,
            'payments' => $payments,
            'carts' => $carts
        ];
        return $data;
    }
}
