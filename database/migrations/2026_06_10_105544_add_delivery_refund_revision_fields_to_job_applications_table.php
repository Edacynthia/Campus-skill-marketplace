<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'delivery_note')) {
                $table->text('delivery_note')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'delivery_file')) {
                $table->string('delivery_file')->nullable();
                $table->json('delivery_screenshots')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'delivery_link')) {
                $table->string('delivery_link')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'revision_count')) {
                $table->integer('revision_count')->default(0);
            }

            if (!Schema::hasColumn('job_applications', 'dispute_reason')) {
                $table->text('dispute_reason')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'disputed_at')) {
                $table->timestamp('disputed_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'refund_reason')) {
                $table->text('refund_reason')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_note',
                'delivery_file',
                'delivery_link',
                'revision_count',
                'dispute_reason',
                'disputed_at',
                'refund_reason',
                'refunded_at',
            ]);
        });
    }
};