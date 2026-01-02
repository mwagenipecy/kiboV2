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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('origin', ['local', 'international'])->default('local');
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
            
            // Pricing
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('TZS');
            $table->decimal('original_price', 15, 2)->nullable();
            $table->boolean('negotiable')->default(true);
            
            // Features & Specifications (JSON)
            $table->json('features')->nullable(); // Air conditioning, Power steering, etc.
            $table->json('safety_features')->nullable(); // ABS, Airbags, etc.
            $table->json('additional_specs')->nullable(); // Additional specifications
            
            // Images
            $table->string('image_front')->nullable();
            $table->string('image_side')->nullable();
            $table->string('image_back')->nullable();
            $table->json('other_images')->nullable(); // Array of additional image URLs
            
            // Documents
            $table->json('documents')->nullable(); // Array of document URLs
            
            // Ownership & Registration
            $table->foreignId('entity_id')->nullable()->constrained()->onDelete('set null'); // Dealer/Owner entity
            $table->foreignId('registered_by')->constrained('users')->onDelete('cascade'); // User who registered
            
            // Status
            $table->enum('status', [
                'pending',
                'awaiting_approval',
                'approved',
                'hold',
                'sold',
                'removed'
            ])->default('pending');
            
            // Additional Information
            $table->text('notes')->nullable(); // Internal notes
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('sold_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // For removed vehicles
            
            // Indexes
            $table->index('status');
            $table->index('origin');
            $table->index('entity_id');
            $table->index(['vehicle_make_id', 'vehicle_model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
