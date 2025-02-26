<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->unique()->after('email');
            $table->string('otp_code')->nullable()->after('phone_number');
            $table->dateTime('otp_expires_at')->nullable()->after('otp_code');
            $table->boolean('has_claimed_coupon')->default(false)->after('otp_expires_at');

            // For admin login
            $table->boolean('is_admin')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('otp_code');
            $table->dropColumn('otp_expires_at');
            $table->dropColumn('has_claimed_coupon');
        });
    }
};
