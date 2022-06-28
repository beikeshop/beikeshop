<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => 0,
            'name' => $this->faker->userName(),
            'phone' => $this->faker->phoneNumber(),
            'country_id' => 0,
            'state_id' => 0,
            'state' => '',
            'city_id' => 0,
            'city' => $this->faker->city(),
            'zipcode' => $this->faker->postcode(),
            'address_1' => $this->faker->streetSuffix(),
            'address_2' => $this->faker->streetName(),
        ];
    }
}
