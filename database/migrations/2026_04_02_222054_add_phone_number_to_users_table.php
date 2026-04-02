<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->unique()->after('email');
        });

        // Back-fill phone_number from existing profile tables
        DB::statement("
            UPDATE users u
            LEFT JOIN customers c ON c.user_id = u.id
            LEFT JOIN entities  e ON e.id = u.entity_id
            LEFT JOIN agents    a ON a.user_id = u.id
            LEFT JOIN cfcs      f ON f.user_id = u.id
            SET u.phone_number = COALESCE(
                CASE WHEN u.role = 'customer' THEN c.phone_number END,
                CASE WHEN u.role IN ('dealer','lender','manufacturer','insurance','service_center') THEN e.phone END,
                CASE WHEN u.role = 'agent' THEN a.phone_number END,
                CASE WHEN u.role = 'cfc'   THEN f.phone_number END
            )
            WHERE u.phone_number IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }
};
