<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin Aguaditas',
            'email' => 'admin@aguaditas.com',
            'password' => bcrypt('password'), // password
            'role' => 'admin',
        ]);

        // Products
        Product::factory(3)->create();

        // Clients
        Client::factory(2)->create();
    }
}
