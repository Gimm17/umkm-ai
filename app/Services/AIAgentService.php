<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    private const MAX_RETRIES = 2;

    private const MAX_HISTORY = 10;

    public function __construct(
        private readonly ContextBuilderService $contextBuilder,
        private readonly OrderExtractorService $orderExtractor,
    ) {}

    /**
     * Proses pesan masuk dan hasilkan balasan AI.
     *
     * @throws \Exception Jika Claude API gagal setelah retry
     */
    public function process(Conversation $conversation, Message $incomingMessage): string
    {
        $context = $this->contextBuilder->build($conversation);

        $response = $this->callClaude($context, attempt: 0);

        // Pisahkan reply teks vs ORDER_DETECTED tag
        [$replyText, $orderData] = $this->parseResponse($response);

        // Proses order jika terdeteksi
        if ($orderData) {
            $this->orderExtractor->save($conversation, $incomingMessage, $orderData);
        }

        return $replyText;
    }

    /**
     * Panggil AI provider (Claude, Gemini, Kimi, atau GLM).
     */
    private function callClaude(array $context, int $attempt): string
    {
        try {
            $provider = AIProviderFactory::getProvider();

            // Build context messages for provider
            $messages = [];
            foreach ($context['messages'] as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ];
            }

            // Call provider
            $response = $provider->generate(
                $context['system'],
                end($messages)['content'], // Last message is the current one
                array_slice($messages, 0, -1) // All messages except the last one (history)
            );

            return $response;

        } catch (\Exception $e) {
            if ($attempt < self::MAX_RETRIES) {
                sleep(1); // backoff singkat

                return $this->callClaude($context, $attempt + 1);
            }

            Log::error('AIAgentService: AI provider gagal setelah retry', [
                'conversation_id' => $context['conversation_id'],
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("AI tidak tersedia: {$e->getMessage()}");
        }
    }

    /**
     * Pisahkan teks balasan dari ORDER_DETECTED tag.
     * Return: [string $replyText, array|null $orderData]
     */
    private function parseResponse(string $raw): array
    {
        $pattern = '/\[ORDER_DETECTED:\s*(\{.*?\})\]/s';

        if (preg_match($pattern, $raw, $matches)) {
            $replyText = trim(preg_replace($pattern, '', $raw));
            $orderData = json_decode($matches[1], true);

            return [$replyText, $orderData];
        }

        return [trim($raw), null];
    }
}
