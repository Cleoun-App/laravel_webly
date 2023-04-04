<?php

namespace Database\Factories\Masters;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Masters\Canteen>
 */
class CanteenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
            'Gembira', 'Sejahtra', 'Cahaya', 'Nur54',
            'Yuvi01', "Semesta", 'Jen7', 'PinkGuy', 'Bersama', '5ribu', "Hidaya", 'Cimol', 'Wira', 'Rejeki',
            'SehatIndah', 'Giasi', 'Cipta Niaga', 'Awet', 'Gonusa'
        ];
        return [
            "name" => "Kantin " . $name[rand(0, count($name) - 1)],
            "image" => fake()->imageUrl,
            "slug" => \Str::random(10),
            "size" => rand(1, 9) . "x" . rand(1, 9),
            "price" => rand(100000, 999999),
            "description" => fake()->sentence,
        ];
    }
}
