<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'otp_code',
        'otp_expires_at',
        'has_claimed_coupon',
    ];

    protected $casts = [
        'otp_expires_at'       => 'datetime',
        'has_claimed_coupon'   => 'boolean',
    ];
}
