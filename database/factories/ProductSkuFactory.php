<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => 0,
            'image' => '',
            'model' => $this->faker->isbn10(),
            'sku' => $this->faker->isbn10(),
            'price' => $this->faker->numberBetween(10, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'is_default' => true,
        ];
    }
}
