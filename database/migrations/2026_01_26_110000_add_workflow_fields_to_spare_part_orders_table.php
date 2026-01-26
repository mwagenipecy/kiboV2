<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            // Agent assignment
            $table->foreignId('agent_id')->nullable()->after('assigned_to')->constrained('agents')->onDelete('set null');
            
            // Quotation fields
            $table->text('quotation_notes')->nullable()->after('quoted_price');
            $table->timestamp('quoted_at')->nullable()->after('quotation_notes');
            
            // User confirmation
            $table->timestamp('user_confirmed_at')->nullable()->after('quoted_at');
            $table->boolean('user_accepted_quote')->nullable()->after('user_confirmed_at');
            
            // Delivery information from agent
            $table->date('estimated_delivery_date')->nullable()->after('user_accepted_quote');
            $table->text('delivery_notes')->nullable()->after('estimated_delivery_date');
            $table->timestamp('delivery_confirmed_at')->nullable()->after('delivery_notes');
            
            // Payment information
            $table->enum('payment_method', ['online', 'offline', 'bank_transfer', 'mobile_money'])->nullable()->after('delivery_confirmed_at');
            $table->json('payment_account_details')->nullable()->after('payment_method');
            $table->string('payment_proof')->nullable()->after('payment_account_details');
            $table->text('payment_notes')->nullable()->after('payment_proof');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_notes');
            $table->boolean('payment_verified')->default(false)->after('payment_submitted_at');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_verified');
            
            // Final delivery
            $table->timestamp('shipped_at')->nullable()->after('payment_verified_at');
            $table->string('tracking_number')->nullable()->after('shipped_at');
            $table->timestamp('delivered_at')->nullable()->after('tracking_number');
        });

        // Update status enum to include new statuses
        DB::statement("ALTER TABLE spare_part_orders MODIFY COLUMN status ENUM('pending', 'processing', 'quoted', 'accepted', 'rejected', 'awaiting_payment', 'payment_submitted', 'payment_verified', 'preparing', 'shipped', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn([
                'agent_id',
                'quotation_notes',
                'quoted_at',
                'user_confirmed_at',
                'user_accepted_quote',
                'estimated_delivery_date',
                'delivery_notes',
                'delivery_confirmed_at',
                'payment_method',
                'payment_account_details',
                'payment_proof',
                'payment_notes',
                'payment_submitted_at',
                'payment_verified',
                'payment_verified_at',
                'shipped_at',
                'tracking_number',
                'delivered_at',
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE spare_part_orders MODIFY COLUMN status ENUM('pending', 'processing', 'quoted', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};

