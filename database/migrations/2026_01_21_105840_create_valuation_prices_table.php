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
        Schema::create('valuation_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Standard Car Valuation", "Urgent Truck Valuation"
            $table->string('type'); // car, truck, house
            $table->foreignId('vehicle_make_id')->nullable()->constrained()->nullOnDelete(); // Optional: specific make
            $table->string('urgency')->default('standard'); // standard, urgent
            $table->decimal('price', 12, 2);
            $table->string('currency', 10)->default('TZS'); // TZS, USD, GBP, EUR
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['type', 'urgency', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valuation_prices');
    }
};
