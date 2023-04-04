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
            "Gedung Merdeka",
            "Gedung Sate",
            "Gedung Kesenian Jakarta",
            "Gedung Nasional Indonesia",
            "Gedung Bank Indonesia",
            "Gedung Pusat Perfilman H. Usmar Ismail",
            "Gedung DPR/MPR RI",
            "Gedung Kementerian Pendidikan dan Kebudayaan",
            "Gedung Graha Merah Putih",
            "Gedung Telkom Landmark Tower",
            "Gedung Wisma 46",
            "Gedung Menara BCA",
            "Gedung Menara Standard Chartered",
            "Gedung Bursa Efek Indonesia",
            "Gedung WTC Jakarta",
            "Gedung Plaza Indonesia",
            "Gedung Pacific Place",
            "Gedung Senayan City",
            "Gedung The Plaza Office Tower",
            "Gedung Wisma Nusantara"
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
