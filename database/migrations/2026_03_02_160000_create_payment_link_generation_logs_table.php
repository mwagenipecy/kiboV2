<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_link_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_link_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('success');
            $table->string('error_message', 500)->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->string('request_id', 100)->nullable();
            $table->timestamps();

            $table->index(['success', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_link_generation_logs');
    }
};
