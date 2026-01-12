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
        Schema::table('vehicle_leases', function (Blueprint $table) {
            // Make vehicle_id nullable since we're adding vehicle data directly
            $table->foreignId('vehicle_id')->nullable()->change();
            
            // Vehicle Basic Information
            $table->string('vehicle_title')->nullable()->after('entity_id');
            $table->integer('vehicle_year')->nullable()->after('vehicle_title');
            $table->string('vehicle_make')->nullable()->after('vehicle_year');
            $table->string('vehicle_model')->nullable()->after('vehicle_make');
            $table->string('vehicle_variant')->nullable()->after('vehicle_model');
            
            // Vehicle Details
            $table->string('body_type')->nullable()->after('vehicle_variant');
            $table->string('fuel_type')->nullable()->after('body_type');
            $table->string('transmission')->nullable()->after('fuel_type');
            $table->string('engine_capacity')->nullable()->after('transmission');
            $table->integer('mileage')->nullable()->after('engine_capacity');
            $table->string('condition')->default('new')->after('mileage');
            $table->string('color_exterior')->nullable()->after('condition');
            $table->integer('seats')->nullable()->after('color_exterior');
            
            // Vehicle Images
            $table->string('image_front')->nullable()->after('seats');
            $table->string('image_side')->nullable()->after('image_front');
            $table->string('image_back')->nullable()->after('image_side');
            $table->json('other_images')->nullable()->after('image_back');
            
            // Vehicle Features
            $table->json('features')->nullable()->after('other_images');
            $table->text('vehicle_description')->nullable()->after('features');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_leases', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_title',
                'vehicle_year',
                'vehicle_make',
                'vehicle_model',
                'vehicle_variant',
                'body_type',
                'fuel_type',
                'transmission',
                'engine_capacity',
                'mileage',
                'condition',
                'color_exterior',
                'seats',
                'image_front',
                'image_side',
                'image_back',
                'other_images',
                'features',
                'vehicle_description',
            ]);
        });
    }
};
