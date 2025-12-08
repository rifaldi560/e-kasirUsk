<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!\App\Models\User::where('email', 'admin@example.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        if (!\App\Models\User::where('email', 'kasir@example.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Kasir User',
                'email' => 'kasir@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }
    }
}
