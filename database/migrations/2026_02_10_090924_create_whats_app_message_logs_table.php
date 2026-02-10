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
        Schema::create('whatsapp_message_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->index();
            $table->enum('direction', ['incoming', 'outgoing'])->index();
            $table->text('message_body');
            $table->string('message_sid', 100)->nullable()->unique()->index();
            $table->string('button_payload', 50)->nullable();
            $table->string('button_text', 255)->nullable();
            $table->string('from_number', 50)->nullable();
            $table->string('to_number', 50)->nullable();
            $table->string('status', 50)->default('sent')->index();
            $table->boolean('used_buttons')->default(false);
            $table->boolean('used_template')->default(false);
            $table->string('template_sid', 100)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->index();
            $table->timestamps();
            
            // Indexes for common queries
            $table->index(['phone_number', 'sent_at']);
            $table->index(['direction', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_logs');
    }
};
