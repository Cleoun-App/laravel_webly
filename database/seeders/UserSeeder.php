<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'admin',
                'username' => 'admin',
                'alamat_ktp' => fake()->address,
                'alamat_sekarang' => fake()->address,
                'kota' => fake()->city,
                'zip' => fake()->numberBetween(1000, 9999),
                'nomor_telp' => fake()->phoneNumber,
                'email' => 'admin@mail.io',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'users',
                'username' => 'users',
                'alamat_ktp' => fake()->address,
                'alamat_sekarang' => fake()->address,
                'kota' => fake()->city,
                'zip' => fake()->numberBetween(1000, 9999),
                'nomor_telp' => fake()->phoneNumber,
                'email' => 'user@mail.io',
                'password' => Hash::make('password'),
            ]
        ];

        foreach($accounts as $account) {
            \App\Models\User::create($account);
        }
    }
}
