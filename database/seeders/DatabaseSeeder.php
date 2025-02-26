<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',      // fill in a name
            'phone_number' => '0000000000',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // hash the password
            'is_admin' => true,
        ]);
    }
}
