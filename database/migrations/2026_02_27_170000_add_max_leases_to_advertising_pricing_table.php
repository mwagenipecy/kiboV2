<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertising_pricing', function (Blueprint $table) {
            $table->unsignedInteger('max_leases')->nullable()->after('max_trucks')->comment('Max number of lease listings with this plan');
        });
    }

    public function down(): void
    {
        Schema::table('advertising_pricing', function (Blueprint $table) {
            $table->dropColumn('max_leases');
        });
    }
};
