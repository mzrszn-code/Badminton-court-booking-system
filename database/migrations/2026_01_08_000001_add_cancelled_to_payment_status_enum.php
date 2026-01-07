<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'cancelled' to the enum list
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_status ENUM('pending', 'completed', 'rejected', 'refunded', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this is risky if 'cancelled' values exist, as they will be truncated or cause errors.
        // For safety in dev, we might just leave it or try to map them back to something else,
        // but traditionally down() reverses the schema change.
        // We will just execute the alter back to original.
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_status ENUM('pending', 'completed', 'rejected', 'refunded') DEFAULT 'pending'");
    }
};
