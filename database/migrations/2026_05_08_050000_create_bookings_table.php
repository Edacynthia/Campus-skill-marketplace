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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['interested', 'confirmed', 'done'])->default('interested');
            $table->text('message')->nullable();
            $table->timestamp('client_confirmed_at')->nullable();
            $table->timestamp('provider_confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['skill_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index(['provider_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
