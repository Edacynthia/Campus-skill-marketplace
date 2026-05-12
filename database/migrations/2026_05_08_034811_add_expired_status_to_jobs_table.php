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
        Schema::table('campus_jobs', function (Blueprint $table) {
            DB::statement("ALTER TABLE campus_jobs MODIFY COLUMN status ENUM('active', 'closed', 'cancelled', 'expired') DEFAULT 'active'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campus_jobs', function (Blueprint $table) {
            DB::statement("ALTER TABLE campus_jobs MODIFY COLUMN status ENUM('active', 'closed', 'cancelled') DEFAULT 'active'");
        });
    }
};
