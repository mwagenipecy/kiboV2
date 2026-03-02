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
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->string('link_id')->nullable()->index();
            $table->string('short_code')->nullable()->index();
            $table->string('payment_url', 500)->nullable();
            $table->string('qr_code_data', 500)->nullable();
            $table->string('target_type', 50)->default('individual');
            $table->boolean('is_public')->default(false);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('currency', 10)->default('TZS');
            $table->string('description')->nullable();
            $table->string('customer_reference')->nullable()->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 50)->nullable();
            $table->string('customer_email')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->boolean('is_reusable')->default(false);
            $table->json('allowed_networks')->nullable();
            $table->string('api_request_id')->nullable();
            $table->string('api_response_at', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_links');
    }
};
