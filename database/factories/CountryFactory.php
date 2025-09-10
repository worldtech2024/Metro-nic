<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->country(),
            'name_ar' => $this->faker->country(),
            'code' => $this->faker->countryCode(),
            'currency' => $this->faker->currencyCode(),
            'mobile' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'faxNumber' => $this->faker->phoneNumber(),
            "workWages" => $this->faker->randomFloat(2, 0, 100),
            "generalCost" => $this->faker->randomFloat(2, 0, 100),
            "profitMargin" => $this->faker->randomFloat(2, 0, 100),
            "tax" => $this->faker->randomFloat(2, 0, 100),
            "wirePrice" => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(['active', 'inactive']),

        ];
    }
}
