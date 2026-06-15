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
    Schema::table('job_applications', function (Blueprint $table) {
        $table->dropUnique('job_applications_job_id_applicant_id_unique');
    });
}

public function down(): void
{
    Schema::table('job_applications', function (Blueprint $table) {
        $table->unique(['job_id', 'applicant_id']);
    });
}
};