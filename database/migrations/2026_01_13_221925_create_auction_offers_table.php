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
        Schema::create('auction_offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_number')->unique();
            $table->foreignId('auction_vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('dealer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('entity_id')->nullable()->constrained('entities')->onDelete('set null');
            
            // Offer Details
            $table->decimal('offer_amount', 12, 2);
            $table->string('currency')->default('TZS');
            $table->text('message')->nullable();
            $table->text('terms')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn', 'countered'])->default('pending');
            
            // Counter Offer
            $table->decimal('counter_amount', 12, 2)->nullable();
            $table->text('counter_message')->nullable();
            $table->timestamp('countered_at')->nullable();
            
            // Response
            $table->timestamp('responded_at')->nullable();
            $table->text('response_message')->nullable();
            
            // Contact Details
            $table->string('dealer_name')->nullable();
            $table->string('dealer_phone')->nullable();
            $table->string('dealer_email')->nullable();
            $table->string('company_name')->nullable();
            
            // Validity
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_offers');
    }
};
