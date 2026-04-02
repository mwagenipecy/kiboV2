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
        // Add column without unique index first
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
        });

        // Back-fill phone_number from profile tables, skipping duplicates.
        // Uses a subquery to resolve the phone, then only sets it when no
        // other user already has that same number.
        $users = DB::select("
            SELECT u.id,
                   COALESCE(
                       CASE WHEN u.role = 'customer' THEN c.phone_number END,
                       CASE WHEN u.role IN ('dealer','lender','manufacturer','insurance','service_center') THEN e.phone END,
                       CASE WHEN u.role = 'agent' THEN a.phone_number END,
                       CASE WHEN u.role = 'cfc'   THEN f.phone_number END
                   ) AS resolved_phone
            FROM users u
            LEFT JOIN customers c ON c.user_id = u.id
            LEFT JOIN entities  e ON e.id = u.entity_id
            LEFT JOIN agents    a ON a.user_id = u.id
            LEFT JOIN cfcs      f ON f.user_id = u.id
            WHERE u.phone_number IS NULL
            ORDER BY u.id ASC
        ");

        $seen = [];
        foreach ($users as $row) {
            $phone = $row->resolved_phone;
            if (empty($phone) || isset($seen[$phone])) {
                continue;
            }
            $seen[$phone] = true;
            DB::table('users')->where('id', $row->id)->update(['phone_number' => $phone]);
        }

        // Now add the unique index
        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone_number');
        });
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
