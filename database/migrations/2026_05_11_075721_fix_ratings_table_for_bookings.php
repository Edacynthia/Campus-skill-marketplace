<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Make application_id and job_id nullable
        // Must drop foreign keys first before modifying the columns
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['job_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->change();
            $table->unsignedBigInteger('job_id')->nullable()->change();
        });

        // Re-add foreign keys as nullable
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('application_id')
                  ->references('id')
                  ->on('job_applications')
                  ->onDelete('cascade');
            $table->foreign('job_id')
                  ->references('id')
                  ->on('campus_jobs')
                  ->onDelete('cascade');
        });

        // Step 2 — Add provider_to_client and client_to_provider to the type enum
        DB::statement("ALTER TABLE ratings MODIFY COLUMN type ENUM(
            'employer_to_worker',
            'worker_to_employer',
            'provider_to_client',
            'client_to_provider'
        ) NOT NULL");
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['job_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable(false)->change();
            $table->unsignedBigInteger('job_id')->nullable(false)->change();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('application_id')
                  ->references('id')
                  ->on('job_applications')
                  ->onDelete('cascade');
            $table->foreign('job_id')
                  ->references('id')
                  ->on('campus_jobs')
                  ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE ratings MODIFY COLUMN type ENUM(
            'employer_to_worker',
            'worker_to_employer'
        ) NOT NULL");
    }
};