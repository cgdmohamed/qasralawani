<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            // Generate an 8-character uppercase coupon code.
            'code'    => strtoupper(Str::random(8)),
            'is_used' => false,
            // used_by and used_at will be assigned later (if coupon is marked as used)
            'used_by' => null,
            'used_at' => null,
        ];
    }
}
