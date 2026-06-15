<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'paystack_reference')) {
                $table->string('paystack_reference')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'escrow_status')) {
                $table->enum('escrow_status', [
                    'not_funded',
                    'funded',
                    'completed',
                    'released',
                    'disputed',
                    'on_hold',
                    'refunded'
                ])->default('not_funded');
            }

            if (!Schema::hasColumn('job_applications', 'escrow_paid_at')) {
                $table->timestamp('escrow_paid_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'worker_completed_at')) {
                $table->timestamp('worker_completed_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'auto_release_at')) {
                $table->timestamp('auto_release_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'escrow_released_at')) {
                $table->timestamp('escrow_released_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'escrow_amount')) {
                $table->decimal('escrow_amount', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('job_applications', 'platform_fee_percent')) {
                $table->decimal('platform_fee_percent', 5, 2)->default(5.00);
            }

            if (!Schema::hasColumn('job_applications', 'platform_fee')) {
                $table->decimal('platform_fee', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('job_applications', 'worker_payout')) {
                $table->decimal('worker_payout', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('job_applications', 'admin_hold')) {
                $table->boolean('admin_hold')->default(false);
            }

            if (!Schema::hasColumn('job_applications', 'admin_hold_reason')) {
                $table->text('admin_hold_reason')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'admin_hold_at')) {
                $table->timestamp('admin_hold_at')->nullable();
            }

            if (!Schema::hasColumn('job_applications', 'admin_hold_by')) {
                $table->foreignId('admin_hold_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (Schema::hasColumn('job_applications', 'admin_hold_by')) {
                $table->dropForeign(['admin_hold_by']);
            }

            $table->dropColumn([
                'paystack_reference',
                'escrow_status',
                'escrow_paid_at',
                'worker_completed_at',
                'auto_release_at',
                'escrow_released_at',
                'escrow_amount',
                'platform_fee_percent',
                'platform_fee',
                'worker_payout',
                'admin_hold',
                'admin_hold_reason',
                'admin_hold_at',
                'admin_hold_by',
            ]);
        });
    }
};