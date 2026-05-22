<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('skill_id')->constrained()->onDelete('cascade');

            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | Booking Status Flow
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'interested',
                'confirmed',
                'in_progress',
                'completed_waiting_payment',
                'done',
                'disputed'
            ])->default('interested');

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'unpaid',
                'client_marked_paid',
                'provider_confirmed_received',
                'payment_disputed'
            ])->default('unpaid');

            /*
            |--------------------------------------------------------------------------
            | Booking Details
            |--------------------------------------------------------------------------
            */

            $table->text('message')->nullable();

            $table->timestamp('completed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('client_paid_at')->nullable();

            $table->timestamp('provider_payment_confirmed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Disputes
            |--------------------------------------------------------------------------
            */

            $table->text('payment_dispute_reason')->nullable();

            $table->timestamp('payment_disputed_at')->nullable();

            $table->timestamp('payment_resolved_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(['skill_id', 'status']);

            $table->index(['client_id', 'status']);

            $table->index(['provider_id', 'status']);

            $table->index(['payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};