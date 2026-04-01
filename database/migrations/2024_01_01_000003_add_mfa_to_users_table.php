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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user'); // admin or user
            $table->string('mfa_code')->nullable();
            $table->timestamp('mfa_code_expires_at')->nullable();
            $table->boolean('mfa_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'mfa_code', 'mfa_code_expires_at', 'mfa_verified']);
        });
    }
};
