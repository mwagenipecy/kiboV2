<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_financing_requests', function (Blueprint $table) {
            $table->id();
            
            // User making the request
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_number')->unique();
            
            // Customer info (for non-logged-in users)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            
            // Request type: 'buy_car' or 'tax_transport'
            $table->enum('request_type', ['buy_car', 'tax_transport'])->default('buy_car');
            
            // For 'buy_car' type - link to car listing
            $table->string('car_link')->nullable();
            $table->json('extracted_car_info')->nullable(); // Extracted info from link
            
            // Vehicle details (manual entry or extracted)
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->unsignedSmallInteger('vehicle_year')->nullable();
            $table->decimal('vehicle_price', 15, 2)->nullable();
            $table->string('vehicle_currency')->default('USD');
            $table->string('vehicle_condition')->nullable(); // new, used
            $table->string('vehicle_location')->nullable(); // country/port
            
            // For 'tax_transport' type
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('transport_cost', 15, 2)->nullable();
            $table->decimal('total_clearing_cost', 15, 2)->nullable();
            
            // Financing details
            $table->decimal('financing_amount_requested', 15, 2)->nullable();
            $table->unsignedInteger('loan_term_months')->nullable();
            $table->decimal('down_payment', 15, 2)->nullable();
            
            // Additional documents/images
            $table->json('documents')->nullable(); // Array of document paths
            
            // Notes
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Workflow status
            $table->enum('status', [
                'pending',           // Initial submission
                'under_review',      // Admin is reviewing
                'approved',          // Admin approved, sent to lenders
                'rejected',          // Admin rejected
                'with_lenders',      // Sent to lenders for offers
                'offer_received',    // Lender(s) made offer(s)
                'accepted',          // Customer accepted an offer
                'completed',         // Financing complete
                'cancelled'          // Cancelled by customer/admin
            ])->default('pending');
            
            // Admin who reviewed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            
            // Selected lender offer
            $table->unsignedBigInteger('accepted_offer_id')->nullable();
            
            $table->timestamps();
        });
        
        // Create table for lender offers on import financing requests
        Schema::create('import_financing_offers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('import_financing_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entity_id')->constrained()->cascadeOnDelete(); // Lender entity
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Lender user who made offer
            
            // Offer details
            $table->decimal('offered_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2); // Annual interest rate
            $table->unsignedInteger('loan_term_months');
            $table->decimal('monthly_payment', 15, 2);
            $table->decimal('processing_fee', 15, 2)->nullable();
            $table->decimal('total_repayment', 15, 2);
            
            // Additional terms
            $table->text('terms_conditions')->nullable();
            $table->text('notes')->nullable();
            
            // Validity
            $table->date('valid_until')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_financing_offers');
        Schema::dropIfExists('import_financing_requests');
    }
};

