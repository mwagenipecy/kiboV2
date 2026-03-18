<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agiza_import_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Customer information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20)->nullable();
            
            // Vehicle information
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->integer('vehicle_year')->nullable();
            $table->string('vehicle_condition')->nullable(); // new, used
            $table->text('vehicle_link')->nullable(); // Link to car listing
            $table->string('source_country')->nullable(); // Country where car is located
            
            // Request details
            $table->enum('request_type', ['with_link', 'already_contacted'])->default('with_link');
            $table->text('dealer_contact_info')->nullable(); // If already contacted dealer
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->string('price_currency', 10)->default('USD');
            $table->text('special_requirements')->nullable();
            $table->text('customer_notes')->nullable();
            
            // Documents/Images
            $table->json('documents')->nullable();
            $table->json('vehicle_images')->nullable();
            
            // Status tracking
            $table->enum('status', [
                'pending',
                'under_review',
                'quote_provided',
                'accepted',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            
            // Admin/Agent handling
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->decimal('quoted_import_cost', 15, 2)->nullable();
            $table->decimal('quoted_total_cost', 15, 2)->nullable();
            $table->string('quote_currency', 10)->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('request_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agiza_import_requests');
    }
};
