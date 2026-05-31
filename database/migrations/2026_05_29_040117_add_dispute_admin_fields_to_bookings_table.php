<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('payment_dispute_opened_by')->nullable()->after('payment_dispute_reason')->constrained('users')->nullOnDelete();

            $table->string('payment_dispute_opened_by_role')->nullable()->after('payment_dispute_opened_by');

            $table->text('client_payment_response')->nullable()->after('payment_disputed_at');

            $table->string('client_payment_proof')->nullable()->after('client_payment_response');

            $table->text('admin_dispute_note')->nullable()->after('client_payment_proof');

            $table->timestamp('admin_payment_deadline_at')->nullable()->after('admin_dispute_note');

            $table->string('dispute_status')->default('open')->after('admin_payment_deadline_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['payment_dispute_opened_by']);

            $table->dropColumn([
                'payment_dispute_opened_by',
                'payment_dispute_opened_by_role',
                'client_payment_response',
                'client_payment_proof',
                'admin_dispute_note',
                'admin_payment_deadline_at',
                'dispute_status',
            ]);
        });
    }
};