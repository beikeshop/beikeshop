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
use Beike\Models\Customer;
use Beike\Models\ProductSku;

class CartService
{
    public static function list($customer)
    {
        if (empty($customer)) {
            return [];
        }
        $cartList = Cart::query()->where('customer_id', $customer->id)->get();
        return $cartList;
    }


    public static function add($sku, int $quantity, $customer = null)
    {
        $customerId = $customer->id ?? 0;
        $productId = $sku->product_id;
        $skuId = $sku->id;

        if (empty($sku)) {
            throw new \Exception("无效的SKU ID");
        }
        $cart = Cart::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->where('product_sku_id', $skuId)
            ->first();

        if ($cart) {
            $cart->selected = true;
            $cart->increment('quantity', $quantity);
        } else {
            $cart = Cart::query()->create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'product_sku_id' => $skuId,
                'quantity' => $quantity,
                'selected' => true,
            ]);
        }
        return $cart;
    }
}
