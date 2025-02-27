<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Required subscriber name
            $table->string('email')->nullable();    // Optional email
            $table->string('phone_number')->unique(); // Required phone number, unique per subscriber
            $table->string('otp_code')->nullable(); // To store the generated OTP
            $table->timestamp('otp_expires_at')->nullable(); // OTP expiration time
            $table->boolean('has_claimed_coupon')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
