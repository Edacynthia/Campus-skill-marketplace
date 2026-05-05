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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('type', ['on_campus', 'off_campus', 'remote'])->default('on_campus');
            $table->enum('urgency', ['normal', 'urgent'])->default('normal');
            $table->decimal('salary', 10, 2);
            $table->string('salary_type')->default('fixed'); // fixed, hourly, monthly
            $table->string('location')->nullable();
            $table->date('deadline')->nullable();
            $table->json('requirements')->nullable(); // Store requirements as JSON
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'closed', 'cancelled'])->default('active');
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'category']);
            $table->index(['salary']);
            $table->index(['deadline']);
            $table->index(['urgency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
