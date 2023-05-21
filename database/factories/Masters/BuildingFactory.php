<?php

namespace Database\Factories\Masters;

use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('ID');

        $nama_gedung = array(
            "Merdeka",
            "Sate",
            "Kesenian Jakarta",
            "Nasional Indonesia",
            "Bank Indonesia",
            "Pusat Perfilman H. Usmar Ismail",
            "DPR/MPR RI",
            "Kementerian Pendidikan dan Kebudayaan",
            "Graha Merah Putih",
            "Telkom Landmark Tower",
            "Wisma 46",
            "Menara BCA",
            "Menara Standard Chartered",
            "Bursa Efek Indonesia",
            "WTC Jakarta",
            "Plaza Indonesia",
            "Pacific Place",
            "Senayan City",
            "The Plaza Office Tower",
            "Wisma Nusantara"
        );

        return [
            'name'  =>  $nama_gedung[$faker->numberBetween(0, count($nama_gedung) - 1)],
            'slug' => uniqid(rand(100, 999)),
            'price' => rand(500000, 9000000),
            'capacity' => rand(1000, 12000),
            'location' => $faker->address,
            'image' => $faker->imageUrl,
            'description' => $faker->sentence,
            'category' => 'gedung serbaguna',
        ];
    }
}
