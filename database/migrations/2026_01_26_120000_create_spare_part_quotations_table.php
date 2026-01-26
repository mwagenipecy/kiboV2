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
        Schema::create('spare_part_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spare_part_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->decimal('quoted_price', 15, 2);
            $table->string('currency', 3)->default('TZS');
            $table->text('quotation_notes')->nullable();
            $table->integer('estimated_days')->nullable(); // Delivery estimate in days
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            // Ensure an agent can only quote once per order
            $table->unique(['spare_part_order_id', 'agent_id']);
        });

        // Add accepted_quotation_id to spare_part_orders
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->foreignId('accepted_quotation_id')->nullable()->after('agent_id')->constrained('spare_part_quotations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->dropForeign(['accepted_quotation_id']);
            $table->dropColumn('accepted_quotation_id');
        });
        
        Schema::dropIfExists('spare_part_quotations');
    }
};

