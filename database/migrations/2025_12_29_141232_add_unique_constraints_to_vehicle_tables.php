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
        Schema::table('vehicle_makes', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->unique(['name', 'vehicle_make_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_makes', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->dropUnique(['name', 'vehicle_make_id']);
        });
    }
};
