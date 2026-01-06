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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('court_name');
            $table->string('court_type')->default('standard');
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
