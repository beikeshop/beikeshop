<?php
/**
 * 创建购物车商品记录
 * \Beike\Models\CartProduct::factory(300)->create(['customer_id'=>2])
 */

namespace Database\Factories\Beike\Models;

use Beike\Models\Cart;
use Beike\Models\CartProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartProductFactory extends Factory
{
    protected $model = CartProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'customer_id' => 0,
            'selected' => 1,
            'product_id' => rand(1, 1000),
            'product_sku' => rand(1, 1000),
            'quantity' => rand(1, 10)
        ];
    }
}
