<?php

use App\Models\Vehicle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('public_id', 26)->nullable()->unique()->after('id');
        });

        foreach (Vehicle::query()->whereNull('public_id')->cursor() as $vehicle) {
            $vehicle->forceFill(['public_id' => (string) Str::ulid()])->saveQuietly();
        }
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};
