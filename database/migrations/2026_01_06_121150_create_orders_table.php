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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_type'); // valuation_report, financing_application, cash_purchase, etc.
            $table->string('status')->default('pending'); // pending, processing, approved, rejected, completed, cancelled
            
            // Financial details
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->boolean('payment_required')->default(false);
            $table->boolean('payment_completed')->default(false);
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Order data (flexible JSON for different order types)
            $table->json('order_data')->nullable();
            
            // Notes and tracking
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Approval/Rejection
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Completion
            $table->timestamp('completed_at')->nullable();
            $table->json('completion_data')->nullable(); // Results, reports, documents, etc.
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['vehicle_id', 'order_type']);
            $table->index('order_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
