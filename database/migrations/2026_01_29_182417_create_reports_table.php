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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // e.g., 'vehicle', 'user', 'listing', etc.
            $table->unsignedBigInteger('reportable_id'); // ID of the reported item
            $table->string('reportable_type'); // Type of the reported item (e.g., Vehicle, User)
            $table->unsignedBigInteger('reporter_id')->nullable(); // User who made the report
            $table->string('reporter_email')->nullable(); // Email if not logged in
            $table->string('reporter_name')->nullable(); // Name if not logged in
            $table->string('reason'); // Reason for report
            $table->text('description')->nullable(); // Additional details
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->text('admin_notes')->nullable(); // Admin notes
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin who reviewed
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['reportable_id', 'reportable_type']);
            $table->index('status');
            $table->index('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
