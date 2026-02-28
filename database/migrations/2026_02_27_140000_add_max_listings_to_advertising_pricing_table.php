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
        Schema::table('advertising_pricing', function (Blueprint $table) {
            $table->unsignedInteger('max_listings')->nullable()->after('duration_days')->comment('Max number of cars that can be listed (active) with this plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertising_pricing', function (Blueprint $table) {
            $table->dropColumn('max_listings');
        });
    }
};
