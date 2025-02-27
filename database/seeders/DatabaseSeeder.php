<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use App\Models\Coupon;

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

        // Create 50 fake subscribers
        Subscriber::factory()->count(50)->create();

        // Create 100 fake coupons
        Coupon::factory()->count(100)->create()->each(function($coupon) {
            // Randomly mark about 50% of the coupons as used.
            if (rand(0, 1)) {
                $coupon->is_used = true;
                // Assign a random subscriber if available.
                $subscriber = Subscriber::inRandomOrder()->first();
                $coupon->used_by = $subscriber ? $subscriber->id : null;
                // Set a used_at date within the last 30 days.
                $coupon->used_at = now()->subDays(rand(0, 30));
                $coupon->save();
            }
        });
    }
}
