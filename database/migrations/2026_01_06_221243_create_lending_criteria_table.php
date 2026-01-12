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
        Schema::create('lending_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Criteria name/title
            $table->text('description')->nullable();
            
            // Vehicle Requirements
            $table->integer('min_vehicle_year')->nullable();
            $table->integer('max_vehicle_year')->nullable();
            $table->decimal('min_vehicle_price', 12, 2)->nullable();
            $table->decimal('max_vehicle_price', 12, 2)->nullable();
            $table->integer('max_mileage')->nullable();
            $table->json('allowed_fuel_types')->nullable(); // ['petrol', 'diesel', 'electric', 'hybrid']
            $table->json('allowed_transmissions')->nullable(); // ['manual', 'automatic']
            $table->json('allowed_body_types')->nullable(); // ['sedan', 'suv', 'truck', etc]
            $table->json('allowed_conditions')->nullable(); // ['new', 'used', 'certified']
            
            // Loan Terms
            $table->decimal('min_loan_amount', 12, 2)->nullable();
            $table->decimal('max_loan_amount', 12, 2)->nullable();
            $table->decimal('interest_rate', 5, 2); // Annual percentage rate
            $table->integer('min_loan_term_months')->default(12); // Minimum months
            $table->integer('max_loan_term_months')->default(84); // Maximum months (7 years)
            $table->decimal('down_payment_percentage', 5, 2); // Minimum down payment %
            
            // Borrower Requirements
            $table->integer('min_credit_score')->nullable();
            $table->decimal('min_monthly_income', 12, 2)->nullable();
            $table->decimal('max_debt_to_income_ratio', 5, 2)->nullable(); // Percentage
            $table->integer('min_employment_months')->nullable();
            $table->boolean('require_collateral')->default(true);
            $table->boolean('require_guarantor')->default(false);
            
            // Additional Terms
            $table->text('additional_requirements')->nullable();
            $table->json('required_documents')->nullable(); // ['id', 'proof_of_income', 'bank_statements', etc]
            $table->integer('processing_time_days')->nullable();
            $table->decimal('processing_fee', 10, 2)->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // For ordering multiple criteria
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('entity_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lending_criteria');
    }
};
