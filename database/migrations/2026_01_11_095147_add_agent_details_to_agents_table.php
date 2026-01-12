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
        Schema::table('agents', function (Blueprint $table) {
            $table->json('vehicle_makes')->nullable()->after('agent_type');
            $table->json('services')->nullable()->after('vehicle_makes');
            $table->text('spare_part_details')->nullable()->after('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['vehicle_makes', 'services', 'spare_part_details']);
        });
    }
};
