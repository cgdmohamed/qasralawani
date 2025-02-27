<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition()
    {
        // Randomly choose a prefix: 70% chance for '05', 30% for '+9665'
        $prefix = $this->faker->boolean(70) ? '05' : '+9665';

        // Generate 8 random digits (using numerify, which replaces '#' with digits)
        $number = $this->faker->numerify('########');

        // Combine the prefix and digits; this produces 10 digits if '05' (2+8) and 13 if '+9665' (5+8)
        $phone = $prefix . $number;

        return [
            'name'         => $this->faker->name,
            'email'        => $this->faker->optional()->safeEmail,
            'phone_number' => $phone,
            // OTP fields can remain null initially
            'otp_code'     => null,
            'otp_expires_at' => null,
            'has_claimed_coupon' => false,
        ];
    }
}
