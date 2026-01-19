<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealer_car_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('car_request_id')->constrained('car_requests')->cascadeOnDelete();
            $table->foreignId('entity_id')->constrained('entities')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedInteger('price')->nullable();
            $table->text('message')->nullable();
            $table->string('image_path')->nullable();

            $table->string('status')->default('submitted'); // submitted|accepted|rejected

            $table->timestamps();

            $table->unique(['car_request_id', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dealer_car_offers');
    }
};


