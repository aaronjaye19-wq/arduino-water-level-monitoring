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
        Schema::create('water_level_readings', function (Blueprint $table) {
            $table->id();
            $table->float('water_level');
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('reading_at');
            $table->timestamps();
            $table->index('reading_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_level_readings');
    }
};
