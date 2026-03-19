<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\AIAgentService;
use App\Services\Contracts\ChannelServiceInterface;
use App\Services\InstagramService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SendAIReply implements ShouldQueue
{
    use Queueable;

    private int $maxRetries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Conversation $conversation,
        private readonly Message $incomingMessage
    ) {
        $this->onQueue('ai-replies');
    }

    /**
     * Execute the job.
     */
    public function handle(AIAgentService $aiAgent): void
    {
        try {
            // Rate limiting untuk mencegah spam AI call
            $key = "ai-reply:{$this->conversation->id}";

            if (RateLimiter::tooManyAttempts($key, $maxAttempts = 1)) {
                Log::info('AI reply rate limited, skipping', [
                    'conversation_id' => $this->conversation->id,
                ]);
                return;
            }

            RateLimiter::hit($key, $decaySeconds = 3);

            // Generate AI reply
            $replyText = $aiAgent->process($this->conversation, $this->incomingMessage);

            // Get appropriate channel service
            $channelService = $this->getChannelService();

            if (!$channelService) {
                throw new \Exception("Unsupported channel: {$this->conversation->channel}");
            }

            // Kirim reply via channel service
            $message = $channelService->sendTextWithRetry($this->conversation, $replyText);

            if ($message) {
                Log::info('AI reply sent successfully', [
                    'conversation_id' => $this->conversation->id,
                    'channel' => $this->conversation->channel,
                    'message_id' => $message->id,
                ]);
            } else {
                throw new \Exception('Failed to send message after retries');
            }

        } catch (\Throwable $e) {
            Log::error('Failed to send AI reply', [
                'conversation_id' => $this->conversation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Graceful degradation: disable AI dan flag ke human
            $this->conversation->update([
                'ai_enabled' => false,
                'status' => 'needs_human',
            ]);

            Log::warning('AI disabled, conversation flagged for human intervention', [
                'conversation_id' => $this->conversation->id,
            ]);

            if ($this->attempts() >= $this->maxRetries) {
                Log::critical('Max retries reached for AI reply', [
                    'conversation_id' => $this->conversation->id,
                ]);
            }

            throw $e;
        }
    }

    /**
     * Get appropriate channel service based on conversation channel.
     */
    private function getChannelService(): ?ChannelServiceInterface
    {
        return match($this->conversation->channel) {
            'whatsapp' => app(WhatsAppService::class),
            'instagram' => app(InstagramService::class),
            default => null,
        };
    }
}
