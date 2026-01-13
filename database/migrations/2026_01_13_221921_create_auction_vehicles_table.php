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
        Schema::create('auction_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('auction_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Vehicle Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('vehicle_make_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');
            $table->string('variant')->nullable();
            $table->integer('year');
            $table->string('condition')->default('used'); // new, used
            $table->string('registration_number')->nullable();
            $table->string('vin')->nullable();
            
            // Specifications
            $table->string('body_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->string('color_exterior')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('mileage')->nullable();
            
            // Pricing
            $table->decimal('asking_price', 12, 2)->nullable();
            $table->decimal('minimum_price', 12, 2)->nullable();
            $table->string('currency')->default('TZS');
            
            // Images
            $table->string('image_front')->nullable();
            $table->json('other_images')->nullable();
            
            // Location
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Contact
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'active', 'closed', 'sold', 'cancelled', 'expired'])->default('pending');
            $table->boolean('is_visible')->default(true);
            $table->boolean('admin_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Auction Settings
            $table->timestamp('auction_start')->nullable();
            $table->timestamp('auction_end')->nullable();
            $table->integer('offer_count')->default(0);
            $table->decimal('highest_offer', 12, 2)->nullable();
            
            // Deal Closure
            $table->foreignId('accepted_offer_id')->nullable();
            $table->timestamp('deal_closed_at')->nullable();
            $table->text('closure_notes')->nullable();
            
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_vehicles');
    }
};
