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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', ['whatsapp', 'instagram'])->comment('Communication channel');
            $table->string('channel_id')->comment('Unique ID from channel (phone number, Instagram ID)');
            $table->string('name')->nullable();
            $table->string('avatar_url')->nullable();
            $table->json('tags')->nullable()->comment('Customer tags for segmentation');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['channel', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
