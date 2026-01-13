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
        Schema::create('advertising_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Basic", "Premium", "Featured"
            $table->enum('category', ['cars', 'trucks', 'garage']); // Category type
            $table->text('description')->nullable(); // Plan description
            $table->decimal('price', 10, 2); // Price amount
            $table->string('currency', 3)->default('GBP'); // Currency code
            $table->integer('duration_days')->nullable(); // Duration in days (null = one-time)
            $table->json('features')->nullable(); // Array of features included
            $table->boolean('is_featured')->default(false); // Highlight this plan
            $table->boolean('is_popular')->default(false); // Mark as popular
            $table->boolean('is_active')->default(true); // Enable/disable plan
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_pricing');
    }
};
