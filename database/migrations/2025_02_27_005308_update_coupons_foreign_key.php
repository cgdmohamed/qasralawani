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
        Schema::table('coupons', function (Blueprint $table) {
            // Drop the existing foreign key constraint on used_by
            $table->dropForeign(['used_by']);
            // Optionally, ensure the used_by column is the proper type (unsignedBigInteger)
            // $table->unsignedBigInteger('used_by')->nullable()->change();

            // Add a new foreign key constraint that references subscribers instead of users
            $table->foreign('used_by')->references('id')->on('subscribers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Drop the foreign key constraint referencing subscribers
            $table->dropForeign(['used_by']);
            // Re-add the original foreign key referencing users
            $table->foreign('used_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
