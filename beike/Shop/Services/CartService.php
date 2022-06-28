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


use Beike\Models\Cart;
use Beike\Models\ProductSku;

class CartService
{
    public static function list($customer)
    {
        $cartList = Cart::query()->where('customer_id', $customer->id)->get();
        return $cartList;
    }


    public static function add($customer, ProductSku $sku, int $quantity)
    {
        $cart = Cart::query()->create([
            'customer_id' => $customer->id,
            'product_id' => $sku->product_id,
            'product_sku_id' => $sku->id,
            'quantity' => $quantity,
            'selected' => true,
        ]);

        return $cart;
    }
}
