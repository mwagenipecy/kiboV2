<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dealer_car_offers', function (Blueprint $table) {
            // Drop existing FK so we can make the column nullable
            $table->dropForeign(['entity_id']);
        });

        Schema::table('dealer_car_offers', function (Blueprint $table) {
            $table->foreignId('entity_id')->nullable()->change();
        });

        Schema::table('dealer_car_offers', function (Blueprint $table) {
            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dealer_car_offers', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
        });

        Schema::table('dealer_car_offers', function (Blueprint $table) {
            $table->foreignId('entity_id')->nullable(false)->change();
        });

        Schema::table('dealer_car_offers', function (Blueprint $table) {
            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->cascadeOnDelete();
        });
    }
};


