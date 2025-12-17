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
        // Panggil Seeder Master Data Dokumen Dulu
        $this->call([
            DocumentSeeder::class,
        ]);

        // 1. Buat Akun Admin
        User::create([
            'full_name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@symbiosis.com',
            'phone_number' => '6281234567890',
            'password_hash' => Hash::make('password'), // Password: password
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Buat Akun User Biasa (Test User)
        User::create([
            'full_name' => 'User Penguji',
            'username' => 'user',
            'email' => 'user@symbiosis.com',
            'phone_number' => '6281234567891',
            'password_hash' => Hash::make('password'), // Password: password
            'role' => 'user',
            'status' => 'active',
        ]);

        // Opsional: Buat 5 user dummy tambahan via Factory
        // User::factory(5)->create();
    }
}
