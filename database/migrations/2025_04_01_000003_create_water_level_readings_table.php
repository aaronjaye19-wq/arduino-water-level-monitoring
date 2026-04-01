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
            $table->string('location')->nullable();
            $table->string('device_id')->nullable()->index();
            $table->enum('status', ['normal', 'warning', 'critical'])->default('normal');
            $table->string('unit')->default('cm');
            $table->timestamps();
        });

        // Index for efficient time-range queries
        Schema::table('water_level_readings', function (Blueprint $table) {
            $table->index('created_at');
            $table->index(['device_id', 'created_at']);
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
