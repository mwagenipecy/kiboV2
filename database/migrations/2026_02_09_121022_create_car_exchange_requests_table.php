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
        Schema::create('car_exchange_requests', function (Blueprint $table) {
            $table->id();
            
            // User and customer info
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('public_token')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            
            // Current vehicle (vehicle being exchanged)
            $table->string('current_vehicle_make')->nullable();
            $table->string('current_vehicle_model')->nullable();
            $table->unsignedSmallInteger('current_vehicle_year')->nullable();
            $table->string('current_vehicle_registration')->nullable();
            $table->unsignedInteger('current_vehicle_mileage')->nullable();
            $table->string('current_vehicle_condition')->nullable(); // excellent, good, fair, poor
            $table->text('current_vehicle_description')->nullable();
            $table->json('current_vehicle_images')->nullable();
            
            // Desired vehicle
            $table->foreignId('desired_vehicle_make_id')->nullable()->constrained('vehicle_makes')->nullOnDelete();
            $table->foreignId('desired_vehicle_model_id')->nullable()->constrained('vehicle_models')->nullOnDelete();
            $table->unsignedSmallInteger('desired_min_year')->nullable();
            $table->unsignedSmallInteger('desired_max_year')->nullable();
            $table->string('desired_fuel_type')->nullable();
            $table->string('desired_transmission')->nullable();
            $table->string('desired_body_type')->nullable();
            $table->unsignedInteger('max_budget')->nullable();
            
            // Additional info
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            
            // Status and workflow
            $table->string('status')->default('pending'); // pending, admin_approved, sent_to_dealers, quotation_sent, completed, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('sent_to_dealer_id')->nullable()->constrained('entities')->nullOnDelete();
            $table->timestamp('sent_to_dealer_at')->nullable();
            $table->unsignedBigInteger('accepted_quotation_id')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_exchange_requests');
    }
};
