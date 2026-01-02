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
        // Add approval fields to customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            $table->foreignId('user_id')->nullable()->after('approval_status')->constrained()->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('user_id');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
        });

        // Add approval fields to cfcs table
        Schema::table('cfcs', function (Blueprint $table) {
            $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            $table->foreignId('user_id')->nullable()->after('approval_status')->constrained()->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('user_id');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
        });

        // Add approval fields to agents table
        Schema::table('agents', function (Blueprint $table) {
            $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            $table->foreignId('user_id')->nullable()->after('approval_status')->constrained()->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('user_id');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['approval_status', 'user_id', 'approved_at', 'approved_by']);
        });

        Schema::table('cfcs', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['approval_status', 'user_id', 'approved_at', 'approved_by']);
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['approval_status', 'user_id', 'approved_at', 'approved_by']);
        });
    }
};
