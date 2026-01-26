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
        // Modify the status enum to include 'quoted'
        DB::statement("ALTER TABLE `garage_service_orders` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'rejected', 'in_progress', 'completed', 'cancelled', 'quoted') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'quoted' from the enum (but first update any 'quoted' records to another status)
        DB::statement("UPDATE `garage_service_orders` SET `status` = 'pending' WHERE `status` = 'quoted'");
        DB::statement("ALTER TABLE `garage_service_orders` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'rejected', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
