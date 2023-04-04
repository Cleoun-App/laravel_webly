<?php

namespace Database\Factories\Masters;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Masters\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name,
            "slug" => \Str::random(10),
            "photo" => fake()->imageUrl,
            "ktp" => fake()->imageUrl,
            "license" => fake()->imageUrl,
            "address" => fake()->address,
            "phone_number" => fake()->phoneNumber,
        ];
    }
}
