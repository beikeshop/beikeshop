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

class CartRepo
{
    public static function createCart(int $customerId)
    {
        $cart = Cart::query()->where('customer_id', $customerId)->first();
        if (empty($cart)) {
            $cart = Cart::query()->create([
                'customer_id' => $customerId,
                'shipping_address_id' => 0,
                'shipping_method_code' => '',
                'payment_address_id' => 0,
                'payment_method_code' => ''
            ]);
        }
        return $cart;
    }
}
