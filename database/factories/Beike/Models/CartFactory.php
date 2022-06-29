<?php
/**
 * 创建购物车商品记录
 * \Beike\Models\Cart::factory(300)->create(['customer_id'=>2])
 */

namespace Database\Factories\Beike\Models;

use Beike\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

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
            'product_sku_id' => rand(1, 1000),
            'quantity' => rand(1, 10)
        ];
    }
}
