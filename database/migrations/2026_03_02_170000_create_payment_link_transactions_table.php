<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_link_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_link_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('TZS');
            $table->string('reference', 100)->nullable();
            $table->string('status', 20)->default('completed'); // pending, completed, failed
            $table->string('payment_method', 50)->nullable(); // e.g. TZ-MPESA-C2B
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['payment_link_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_link_transactions');
    }
};
