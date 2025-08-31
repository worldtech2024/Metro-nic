<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => 'admin',
            'name' => 'admin',
            'phone' => '01061164326',
            'email' => 'mahmoudzakarya130@gmail.com',
            'password' => Hash::make('123Mezo@123'),
            'role' => 'admin',
            'permissions' => [
                'create_estimation',
                'price_estimation',
                'approve_estimation',
                
            ],
        ];
    }
}