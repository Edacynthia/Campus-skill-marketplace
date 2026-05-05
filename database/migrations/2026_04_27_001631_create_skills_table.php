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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->string('price_type')->default('fixed'); // fixed, hourly, per_project
            $table->string('price_unit')->nullable(); // hr, pg, event, etc.
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('views_count')->default(0);
            $table->integer('orders_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'category']);
            $table->index(['price']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
