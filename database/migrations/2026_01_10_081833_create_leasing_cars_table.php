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
        Schema::create('leasing_cars', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('registration_number')->nullable()->unique();
            $table->enum('condition', ['new', 'used', 'certified_pre_owned'])->default('used');
            
            // Make and Model
            $table->foreignId('vehicle_make_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');
            $table->string('variant')->nullable();
            $table->year('year');
            
            // Specifications
            $table->string('body_type')->nullable(); // Sedan, SUV, Hatchback, etc.
            $table->string('fuel_type')->nullable(); // Petrol, Diesel, Electric, Hybrid
            $table->string('transmission')->nullable(); // Manual, Automatic
            $table->string('engine_capacity')->nullable(); // e.g., "2.0L"
            $table->integer('engine_cc')->nullable(); // e.g., 2000
            $table->string('drive_type')->nullable(); // FWD, RWD, AWD, 4WD
            $table->string('color_exterior')->nullable();
            $table->string('color_interior')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('mileage')->nullable(); // in kilometers
            $table->string('vin')->nullable(); // Vehicle Identification Number
            
            // Leasing Pricing
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2);
            $table->string('currency', 3)->default('TZS');
            $table->boolean('negotiable')->default(false);
            
            // Leasing Terms
            $table->integer('min_lease_days')->default(1);
            $table->integer('max_lease_days')->nullable();
            $table->decimal('mileage_limit_per_day', 8, 2)->nullable(); // km per day
            $table->decimal('excess_mileage_charge', 8, 2)->nullable(); // per km
            $table->integer('min_driver_age')->default(21);
            $table->boolean('insurance_included')->default(true);
            $table->boolean('fuel_included')->default(false);
            $table->text('lease_terms')->nullable(); // Additional terms and conditions
            
            // Features & Specifications (JSON)
            $table->json('features')->nullable(); // Air conditioning, Power steering, etc.
            $table->json('safety_features')->nullable(); // ABS, Airbags, etc.
            $table->json('additional_specs')->nullable(); // Additional specifications
            
            // Images
            $table->string('image_front')->nullable();
            $table->string('image_side')->nullable();
            $table->string('image_back')->nullable();
            $table->string('image_interior')->nullable();
            $table->json('other_images')->nullable(); // Array of additional image URLs
            
            // Documents
            $table->json('documents')->nullable(); // Insurance, registration, etc.
            
            // Ownership & Registration
            $table->foreignId('entity_id')->nullable()->constrained()->onDelete('set null'); // Dealer/Owner entity
            $table->foreignId('registered_by')->constrained('users')->onDelete('cascade'); // User who registered
            
            // Availability Status
            $table->enum('status', [
                'pending',
                'available',
                'leased',
                'maintenance',
                'unavailable',
                'removed'
            ])->default('pending');
            
            // Additional Information
            $table->text('notes')->nullable(); // Internal notes
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Statistics
            $table->integer('total_leases')->default(0);
            $table->integer('view_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('entity_id');
            $table->index(['vehicle_make_id', 'vehicle_model_id']);
            $table->index('daily_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leasing_cars');
    }
};
