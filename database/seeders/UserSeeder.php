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
                'email' => 'admin@mail.io',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'users',
                'email' => 'user@mail.io',
                'password' => Hash::make('password'),
            ]
        ];

        foreach($accounts as $account) {
            \App\Models\User::create($account);
        }
    }
}
