<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\Contracts\ChannelServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService implements ChannelServiceInterface
{
    /**
     * Send text message via Instagram Graph API.
     */
    public function sendText(Conversation $conversation, string $content): Message
    {
        $accessToken = config('services.instagram.access_token');
        $baseUrl = config('services.instagram.url');
        $instagramId = $conversation->contact->channel_id;

        try {
            // Instagram Graph API uses the Conversations endpoint
            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post("{$baseUrl}/me/messages", [
                    'recipient' => [
                        'comment_id' => $instagramId,
                    ],
                    'message' => [
                        'text' => $content,
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception("Instagram API error: {$response->status()}");
            }

            // Simpan message record
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'direction' => 'outbound',
                'content' => $content,
                'message_type' => 'text',
                'sent_by' => 'ai',
                'raw_payload' => $response->json(),
            ]);

            // Update conversation last_message_at
            $conversation->update([
                'last_message_at' => now(),
            ]);

            Log::info('Instagram message sent successfully', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'to' => $instagramId,
            ]);

            return $message;

        } catch (\Throwable $e) {
            Log::error('Failed to send Instagram message', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send text message via Instagram with retry mechanism.
     */
    public function sendTextWithRetry(Conversation $conversation, string $content, int $maxRetries = 3): ?Message
    {
        $lastException = null;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                return $this->sendText($conversation, $content);
            } catch (\Throwable $e) {
                $lastException = $e;

                if ($attempt < $maxRetries - 1) {
                    sleep(1); // backoff singkat
                }
            }
        }

        Log::error('Failed to send Instagram message after retries', [
            'conversation_id' => $conversation->id,
            'attempts' => $maxRetries,
            'error' => $lastException?->getMessage(),
        ]);

        return null;
    }

    public function getChannelName(): string
    {
        return 'instagram';
    }
}
