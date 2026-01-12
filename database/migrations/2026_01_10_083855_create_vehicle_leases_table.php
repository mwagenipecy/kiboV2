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
        Schema::create('vehicle_leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entity_id')->nullable()->constrained()->nullOnDelete(); // Dealer/Entity
            
            // Lease Terms
            $table->string('lease_title');
            $table->text('lease_description')->nullable();
            $table->decimal('monthly_payment', 10, 2);
            $table->integer('lease_term_months'); // e.g., 12, 24, 36, 48
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->integer('mileage_limit_per_year')->nullable(); // e.g., 10000, 15000, 20000
            $table->decimal('excess_mileage_charge', 10, 2)->nullable(); // charge per km/mile
            
            // Additional Costs
            $table->decimal('acquisition_fee', 10, 2)->default(0);
            $table->decimal('disposition_fee', 10, 2)->default(0); // End of lease fee
            $table->boolean('maintenance_included')->default(false);
            $table->boolean('insurance_included')->default(false);
            
            // Eligibility & Requirements
            $table->integer('min_credit_score')->nullable();
            $table->decimal('min_monthly_income', 10, 2)->nullable();
            $table->integer('min_age')->default(21);
            $table->text('additional_requirements')->nullable();
            
            // Purchase Options
            $table->boolean('purchase_option_available')->default(true);
            $table->decimal('residual_value', 10, 2)->nullable(); // Buy-out price at end
            $table->decimal('early_termination_fee', 10, 2)->nullable();
            
            // Status & Availability
            $table->enum('status', ['active', 'inactive', 'reserved'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            
            // Additional Info
            $table->json('terms_conditions')->nullable();
            $table->json('included_services')->nullable(); // roadside assistance, warranty, etc.
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('monthly_payment');
            $table->index('lease_term_months');
            $table->index(['is_featured', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_leases');
    }
};
