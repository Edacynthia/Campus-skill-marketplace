<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
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
            $table->dropColumn([
                'escrow_amount',
                'platform_fee_percent',
                'platform_fee',
                'provider_payout',
                'client_deleted_at',
                'provider_deleted_at',
            ]);
        });
    }
};