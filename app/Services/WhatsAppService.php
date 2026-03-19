<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\Contracts\ChannelServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService implements ChannelServiceInterface
{
    /**
     * Kirim pesan text ke WhatsApp.
     */
    public function sendText(Conversation $conversation, string $content): Message
    {
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');
        $baseUrl = config('services.whatsapp.url');

        try {
            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post("{$baseUrl}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $conversation->contact->channel_id,
                    'type' => 'text',
                    'text' => [
                        'body' => $content,
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception("WhatsApp API error: {$response->status()}");
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

            Log::info('WhatsApp message sent successfully', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'to' => $conversation->contact->channel_id,
            ]);

            return $message;

        } catch (\Throwable $e) {
            Log::error('Failed to send WhatsApp message', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Kirim pesan ke WhatsApp dengan retry.
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

        Log::error('Failed to send WhatsApp message after retries', [
            'conversation_id' => $conversation->id,
            'attempts' => $maxRetries,
            'error' => $lastException?->getMessage(),
        ]);

        return null;
    }

    public function getChannelName(): string
    {
        return 'whatsapp';
    }
}
