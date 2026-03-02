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
        Schema::create('payment_link_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_link_id')->constrained()->cascadeOnDelete();
            $table->string('item_code')->nullable()->index();
            $table->string('type', 50)->default('service');
            $table->string('product_service_reference')->nullable();
            $table->string('product_service_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('minimum_amount', 15, 2)->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('allow_partial')->default(false);
            $table->string('payment_status', 20)->default('unpaid'); // unpaid, partial, paid
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['payment_link_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_link_items');
    }
};
