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

namespace Beike\Services;


use Beike\Models\Cart;
use Beike\Models\ProductSku;

class CartService
{
    public function add(ProductSku $sku, int $quantity)
    {
        $cart = Cart::create([
            'customer_id' => 0,
            'product_id' => $sku->product_id,
            'product_sku_id' => $sku->id,
            'quantity' => $quantity,
            'selected' => true,
        ]);

        return $cart;
    }
}
