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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['whatsapp', 'instagram'])->comment('Communication channel');
            $table->enum('status', ['open', 'resolved', 'pending', 'needs_human'])->default('open')->comment('Conversation status');
            $table->boolean('ai_enabled')->default(true)->comment('Whether AI auto-reply is enabled');
            $table->timestamp('last_message_at')->nullable()->comment('Timestamp of last message');
            $table->unsignedInteger('unread_count')->default(0)->comment('Count of unread messages');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['channel', 'status']);
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
