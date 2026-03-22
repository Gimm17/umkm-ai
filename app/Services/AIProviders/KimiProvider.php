<?php

namespace App\Services\AIProviders;

use App\Services\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KimiProvider implements AIProviderInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://api.moonshot.cn/v1';
    private string $model = 'moonshot-v1-8k'; // Free model

    public function __construct()
    {
        $this->apiKey = config('services.kimi.api_key');
    }

    public function generate(string $systemPrompt, string $userMessage, array $context = []): string
    {
        try {
            // Build messages array
            $messages = [];

            // Add system prompt
            if (!empty($systemPrompt)) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ];
            }

            // Add context/history
            foreach ($context as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ];
            }

            // Add current user message
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage,
            ];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 2048,
                ]);

            if ($response->failed()) {
                Log::error('Kimi API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Kimi API error: {$response->status()}");
            }

            $data = $response->json();

            // Extract text from Kimi response
            $text = data_get($data, 'choices.0.message.content', '');

            if (empty($text)) {
                throw new \Exception('Empty response from Kimi API');
            }

            return $text;

        } catch (\Throwable $e) {
            Log::error('Kimi provider error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getProviderName(): string
    {
        return 'kimi';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
