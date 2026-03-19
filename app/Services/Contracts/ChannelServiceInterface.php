<?php

namespace App\Services\Contracts;

use App\Models\Conversation;
use App\Models\Message;

interface ChannelServiceInterface
{
    /**
     * Send text message via channel.
     */
    public function sendText(Conversation $conversation, string $content): Message;

    /**
     * Send text message with retry mechanism.
     */
    public function sendTextWithRetry(Conversation $conversation, string $content, int $maxRetries = 3): ?Message;

    /**
     * Get channel name.
     */
    public function getChannelName(): string;
}
