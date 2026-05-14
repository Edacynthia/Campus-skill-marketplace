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
        Schema::table('users', function (Blueprint $table) {
            // Add approval status column if it doesn't exist
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->enum('approval_status', ['approved', 'pending', 'rejected'])
                    ->default('pending')
                    ->after('is_approved');
            }

            // Add approved_at timestamp if it doesn't exist
            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval_status');
            }

            // Add approved_by foreign key if it doesn't exist
            if (!Schema::hasColumn('users', 'approved_by')) {
                $table->foreignId('approved_by')
                    ->nullable()
                    ->after('approved_at')
                    ->constrained('users')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }

            if (Schema::hasColumn('users', 'approved_at')) {
                $table->dropColumn('approved_at');
            }

            if (Schema::hasColumn('users', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
