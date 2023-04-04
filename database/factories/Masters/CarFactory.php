<?php

namespace Database\Factories\Masters;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Masters\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
            'Buggati Chyron', 'Lamborgini Adventador',
            'Verrari Vteo', 'Chevrolet', 'Supra', 'Suzuki Avanza',
            'Senia', 'Honda Rizz', 'Mercedez benz', 'Mustang GT', 'Mitshubisi Hano L300', 'BMW', 'Mini Copper'
        ];

        $type = ['sport', 'sedan', 'double', 'engkel', 'l300', 'fuso', 'buildbox'];

        $leading = ['DB', 'DM', 'DT', 'DD', 'DK', 'DS', 'DW'];
        return [
            "name" => $name[rand(0, count($name) - 1)],
            "type" => strtoupper($type[rand(0, count($type) - 1)]),
            "km" => rand(100, 999999),
            "license_plate" => strtoupper($leading[rand(0, count($leading) - 1)] . rand(1000, 9999) . \Str::random('2')),
            "stnk" => fake()->imageUrl,
            "description" => fake()->sentence,
            "price" => rand(100000, 9999999),
            "slug" => \Str::random(20),
            "image" => fake()->imageUrl,
        ];
    }
}
