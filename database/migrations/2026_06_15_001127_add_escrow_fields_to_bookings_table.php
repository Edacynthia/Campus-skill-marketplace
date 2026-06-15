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
    Schema::table('bookings', function (Blueprint $table) {
        if (!Schema::hasColumn('bookings', 'escrow_status')) {
            $table->enum('escrow_status', [
                'not_funded', 'funded', 'completed',
                'released', 'disputed', 'on_hold', 'release_pending'
            ])->default('not_funded')->after('status');
        }
        if (!Schema::hasColumn('bookings', 'paystack_reference')) {
            $table->string('paystack_reference')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'escrow_paid_at')) {
            $table->timestamp('escrow_paid_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'escrow_released_at')) {
            $table->timestamp('escrow_released_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'provider_completed_at')) {
            $table->timestamp('provider_completed_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'auto_release_at')) {
            $table->timestamp('auto_release_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'escrow_amount')) {
            $table->decimal('escrow_amount', 10, 2)->default(0);
        }
        if (!Schema::hasColumn('bookings', 'platform_fee_percent')) {
            $table->decimal('platform_fee_percent', 5, 2)->default(5.00);
        }
        if (!Schema::hasColumn('bookings', 'platform_fee')) {
            $table->decimal('platform_fee', 10, 2)->default(0);
        }
        if (!Schema::hasColumn('bookings', 'provider_payout')) {
            $table->decimal('provider_payout', 10, 2)->default(0);
        }
        if (!Schema::hasColumn('bookings', 'admin_hold')) {
            $table->boolean('admin_hold')->default(false);
        }
        if (!Schema::hasColumn('bookings', 'admin_hold_reason')) {
            $table->text('admin_hold_reason')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'admin_hold_at')) {
            $table->timestamp('admin_hold_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'admin_hold_by')) {
            $table->foreignId('admin_hold_by')->nullable()->constrained('users')->nullOnDelete();
        }
        if (!Schema::hasColumn('bookings', 'client_deleted_at')) {
            $table->timestamp('client_deleted_at')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'provider_deleted_at')) {
            $table->timestamp('provider_deleted_at')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        if (Schema::hasColumn('bookings', 'admin_hold_by')) {
            $table->dropForeign(['admin_hold_by']);
        }

        $table->dropColumn([
            'escrow_status',
            'paystack_reference',
            'escrow_paid_at',
            'escrow_released_at',
            'provider_completed_at',
            'auto_release_at',
            'escrow_amount',
            'platform_fee_percent',
            'platform_fee',
            'provider_payout',
            'admin_hold',
            'admin_hold_reason',
            'admin_hold_at',
            'admin_hold_by',
            'client_deleted_at',
            'provider_deleted_at',
        ]);
    });
}
};
