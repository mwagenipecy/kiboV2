<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('page_hero_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_hero_id')->constrained('page_heroes')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('headline')->nullable();
            $table->text('subheadline')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->string('overlay_style', 32)->default('dark_bottom');
            $table->string('text_align', 16)->default('center');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['slug' => 'cars', 'label' => 'Cars listing (/cars)'],
            ['slug' => 'trucks', 'label' => 'Trucks listing (/trucks)'],
            ['slug' => 'vans', 'label' => 'Vans listing (/vans)'],
            ['slug' => 'spare_parts', 'label' => 'Spare parts (/spare-parts)'],
            ['slug' => 'home', 'label' => 'Customer home'],
        ];

        foreach ($rows as $row) {
            DB::table('page_heroes')->insert([
                'slug' => $row['slug'],
                'label' => $row['label'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_hero_slides');
        Schema::dropIfExists('page_heroes');
    }
};
