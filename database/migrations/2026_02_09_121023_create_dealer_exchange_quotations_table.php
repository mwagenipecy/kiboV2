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
        Schema::create('dealer_exchange_quotations', function (Blueprint $table) {
            $table->id();
            
            // Exchange request reference
            $table->foreignId('exchange_request_id')->constrained('car_exchange_requests')->onDelete('cascade');
            
            // Dealer info
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Quotation details
            $table->decimal('current_vehicle_valuation', 15, 2); // Value of customer's current vehicle
            $table->decimal('desired_vehicle_price', 15, 2); // Price of desired vehicle
            $table->decimal('price_difference', 15, 2); // Amount customer needs to pay (can be negative)
            $table->string('currency', 3)->default('TZS');
            
            // Vehicle offered (if dealer has a specific vehicle)
            $table->foreignId('offered_vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            
            // Quotation message
            $table->text('message')->nullable();
            $table->json('quotation_documents')->nullable(); // PDFs, images, etc.
            
            // Status
            $table->string('status')->default('pending'); // pending, sent, accepted, rejected
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('exchange_request_id');
            $table->index('entity_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_exchange_quotations');
    }
};
