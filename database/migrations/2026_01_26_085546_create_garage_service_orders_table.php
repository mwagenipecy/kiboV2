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
        Schema::create('garage_service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            // User information
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Garage/Agent information
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('service_type'); // e.g., oil_change, tire_rotation, etc.
            
            // Vehicle information
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_registration')->nullable();
            
            // Booking details
            $table->enum('booking_type', ['immediate', 'scheduled'])->default('scheduled');
            $table->timestamp('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->text('service_description')->nullable();
            $table->text('customer_notes')->nullable();
            
            // Status and processing
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Quotation
            $table->decimal('quoted_price', 15, 2)->nullable();
            $table->string('currency', 3)->default('TZS');
            $table->text('quotation_notes')->nullable();
            $table->timestamp('quoted_at')->nullable();
            
            // Completion
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('order_number');
            $table->index('status');
            $table->index('user_id');
            $table->index('agent_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_service_orders');
    }
};
