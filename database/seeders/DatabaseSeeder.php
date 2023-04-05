<?php

namespace Database\Seeders;

use App\Models\Masters\Building;
use App\Models\Masters\Canteen;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {

            $this->call([
                UserSeeder::class,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $bf = Building::factory(10)->create();
        $cf = Canteen::factory(10)->create();
        $rf = Car::factory(10)->create();
        $df = Driver::factory(10)->create();
    }
}
