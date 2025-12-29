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
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // dealer, lender, manufacturer, etc.
            $table->string('status')->default('pending'); // pending, active, suspended, inactive, rejected
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('Kenya');
            $table->string('registration_number')->nullable()->unique();
            $table->string('tax_id')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // For additional flexible data
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
