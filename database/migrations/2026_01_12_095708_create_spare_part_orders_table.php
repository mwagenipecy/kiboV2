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
        Schema::create('spare_part_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            // User information
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Vehicle information
            $table->foreignId('vehicle_make_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');
            $table->enum('condition', ['new', 'used'])->default('new');
            $table->string('part_name')->nullable();
            $table->text('description')->nullable();
            
            // Images
            $table->json('images')->nullable(); // Array of image paths
            
            // Delivery information
            $table->text('delivery_address');
            $table->string('delivery_city')->nullable();
            $table->string('delivery_region')->nullable();
            $table->string('delivery_country')->default('Tanzania');
            $table->string('delivery_postal_code')->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            
            // Contact information
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            
            // Order status
            $table->enum('status', ['pending', 'processing', 'quoted', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // Pricing (if quoted)
            $table->decimal('quoted_price', 15, 2)->nullable();
            $table->string('currency', 3)->default('TZS');
            
            $table->timestamps();
            
            // Indexes
            $table->index('order_number');
            $table->index('status');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_orders');
    }
};
