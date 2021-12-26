<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => 0,
            'image' => 'path/to/image.jpg',
            'video' => '',
            'sort_order' => 0,
            'status' => true,
            'variable' => '',
        ];
    }
}
