<?php

use App\Models\SparePartOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->string('public_token', 48)->nullable()->unique()->after('order_number');
        });

        SparePartOrder::query()->whereNull('public_token')->each(function (SparePartOrder $order) {
            $order->forceFill(['public_token' => SparePartOrder::generatePublicToken()])->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
