<?php

namespace App\Jobs;

use App\Jobs\SendAIReply;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessIncomingMessage implements ShouldQueue
{
    use Queueable;

    private int $maxRetries = 3;
    private int $cacheDuration = 24 * 3600; // 24 hours

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $channel,
        private readonly array $payload
    ) {
        $this->onQueue('messages');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 1. Parse payload untuk mendapatkan message data
            $messageData = $this->parsePayload();

            if (!$messageData) {
                Log::info('Bukan pesan, skip processing', [
                    'channel' => $this->channel,
                    'payload' => $this->payload,
                ]);
                return;
            }

            // 2. Cek idempotency - skip jika sudah diproses
            $messageId = $this->extractMessageId($messageData);
            $cacheKey = "processed_webhook:{$this->channel}:{$messageId}";

            if (Cache::has($cacheKey)) {
                Log::info('Duplicate webhook, skip processing', [
                    'message_id' => $messageId,
                    'channel' => $this->channel,
                ]);
                return;
            }

            // 3. Find or create Contact
            $contact = $this->findOrCreateContact($messageData);

            // 4. Find or create Conversation
            $conversation = $this->findOrCreateConversation($contact);

            // 5. Create Message record
            $message = $this->createMessage($conversation, $messageData);

            // 6. Mark as processed
            Cache::put($cacheKey, true, now()->addSeconds($this->cacheDuration));

            // 7. Trigger AI reply jika AI enabled
            if ($conversation->ai_enabled) {
                SendAIReply::dispatch($conversation, $message)
                    ->onQueue('ai-replies');
            }

            Log::info('Message processed successfully', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'channel' => $this->channel,
            ]);

        } catch (\Throwable $e) {
            Log::error('Failed to process incoming message', [
                'channel' => $this->channel,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($this->attempts() >= $this->maxRetries) {
                Log::critical('Max retries reached, dropping message', [
                    'channel' => $this->channel,
                    'payload' => $this->payload,
                ]);
            }

            throw $e;
        }
    }

    /**
     * Parse payload dan extract message data.
     */
    private function parsePayload(): ?array
    {
        if ($this->channel === 'whatsapp') {
            return $this->parseWhatsAppPayload();
        }

        if ($this->channel === 'instagram') {
            return $this->parseInstagramPayload();
        }

        return null;
    }

    /**
     * Parse WhatsApp payload.
     */
    private function parseWhatsAppPayload(): ?array
    {
        $entry = data_get($this->payload, 'entry.0');
        $changes = data_get($entry, 'changes.0');
        $value = data_get($changes, 'value');
        $message = data_get($value, 'messages.0');

        if (!$message) {
            return null;
        }

        $contact = data_get($value, 'contacts.0');

        return [
            'message_id' => $message['id'],
            'sender_id' => $message['from'],
            'sender_name' => data_get($contact, 'profile.name', 'Unknown'),
            'content' => data_get($message, 'text.body', ''),
            'type' => $message['type'],
            'timestamp' => $message['timestamp'],
        ];
    }

    /**
     * Parse Instagram payload.
     */
    private function parseInstagramPayload(): ?array
    {
        // Instagram Graph API webhook structure
        $entry = data_get($this->payload, 'entry.0');

        if (!$entry) {
            return null;
        }

        // Instagram messaging webhook
        $messaging = data_get($entry, 'messaging.0');

        if (!$messaging) {
            return null;
        }

        $message = data_get($messaging, 'message');

        if (!$message) {
            return null;
        }

        // Extract message content
        $content = '';
        $type = 'text';

        // Handle text message
        if (isset($message['text'])) {
            $content = $message['text'];
            $type = 'text';
        }
        // Handle attachment (image, video, etc.)
        elseif (isset($message['attachments'])) {
            $attachment = $message['attachments']['data'][0] ?? [];
            $content = data_get($attachment, 'type', 'attachment');
            $type = data_get($attachment, 'type', 'text');
        }

        // Get sender info
        $senderId = data_get($messaging, 'sender.id');
        $senderName = data_get($messaging, 'sender.name', 'Unknown');

        return [
            'message_id' => $message['mid'] ?? uniqid('ig_'),
            'sender_id' => $senderId,
            'sender_name' => $senderName,
            'content' => $content,
            'type' => $type,
            'timestamp' => data_get($message, 'timestamp', time()),
            'raw_payload' => $message,
        ];
    }

    /**
     * Extract unique message ID for idempotency check.
     */
    private function extractMessageId(array $messageData): string
    {
        return $messageData['message_id'];
    }

    /**
     * Find or create Contact.
     */
    private function findOrCreateContact(array $messageData): Contact
    {
        return Contact::updateOrCreate(
            [
                'channel' => $this->channel,
                'channel_id' => $messageData['sender_id'],
            ],
            [
                'name' => $messageData['sender_name'],
            ]
        );
    }

    /**
     * Find or create Conversation.
     */
    private function findOrCreateConversation(Contact $contact): Conversation
    {
        return Conversation::firstOrCreate(
            [
                'contact_id' => $contact->id,
                'channel' => $this->channel,
                'status' => 'open',
            ],
            [
                'ai_enabled' => true,
                'last_message_at' => now(),
            ]
        )->fresh();
    }

    /**
     * Create Message record.
     */
    private function createMessage(Conversation $conversation, array $messageData): Message
    {
        // Update conversation last_message_at and increment unread
        $conversation->update([
            'last_message_at' => now(),
        ]);
        $conversation->incrementUnread();

        return Message::create([
            'conversation_id' => $conversation->id,
            'direction' => 'inbound',
            'content' => $messageData['content'],
            'message_type' => $this->mapMessageType($messageData['type']),
            'raw_payload' => $this->payload,
        ]);
    }

    /**
     * Map message type from channel to our enum.
     */
    private function mapMessageType(string $channelType): string
    {
        return match($channelType) {
            'text', 'message' => 'text',
            'image', 'photo' => 'image',
            'audio', 'voice' => 'audio',
            default => 'text',
        };
    }

    public function uniqueId(): string
    {
        return $this->channel . '-' . md5(json_encode($this->payload));
    }
}
