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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Spatie Roles & Permissions
            $table->string('role')->default('user');   // 'user' or 'admin'

            // University related fields (optional)
            $table->string('department')->nullable();
            $table->string('matric_number')->nullable();
            $table->string('staff_id')->nullable();

            // For external / non-university users
            $table->string('passport_photo')->nullable();
            $table->boolean('is_approved')->default(false);

            $table->rememberToken();
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('email', 'idx_users_email');
            $table->index('is_approved', 'idx_users_approved');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
