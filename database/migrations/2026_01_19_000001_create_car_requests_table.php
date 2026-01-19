<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('public_token')->unique();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->foreignId('vehicle_make_id')->nullable()->constrained('vehicle_makes')->nullOnDelete();
            $table->foreignId('vehicle_model_id')->nullable()->constrained('vehicle_models')->nullOnDelete();

            $table->unsignedSmallInteger('min_year')->nullable();
            $table->unsignedSmallInteger('max_year')->nullable();
            $table->unsignedInteger('min_budget')->nullable();
            $table->unsignedInteger('max_budget')->nullable();

            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();

            $table->string('status')->default('open'); // open|closed
            // Nullable reference to accepted dealer_car_offers.id (kept without FK to avoid migration ordering issues)
            $table->unsignedBigInteger('accepted_offer_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_requests');
    }
};


