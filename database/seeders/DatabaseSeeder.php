<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lovebite.com'],
            [
                'name' => 'Love Bite Admin',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@lovebite.com'],
            [
                'name' => 'Love Bite Customer',
                'role' => 'customer',
                'password' => Hash::make('password123'),
            ]
        );

        $this->call(MenuSeeder::class);
    }
}
