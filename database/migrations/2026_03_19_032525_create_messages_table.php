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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->enum('direction', ['inbound', 'outbound'])->comment('Message direction');
            $table->text('content')->comment('Message text content');
            $table->enum('message_type', ['text', 'image', 'audio', 'order_detected'])->default('text')->comment('Type of message');
            $table->enum('sent_by', ['ai', 'human'])->nullable()->comment('Who sent the message (for outbound)');
            $table->json('raw_payload')->nullable()->comment('Original payload from webhook');
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
