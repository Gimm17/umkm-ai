<?php

namespace App\Services\AIProviders;

use App\Services\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    private string $apiKey;

    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    private string $model = 'gemini-1.5-flash'; // Free model

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generate(string $systemPrompt, string $userMessage, array $context = []): string
    {
        try {
            // Build messages array
            $contents = [];

            // Add system prompt
            if (! empty($systemPrompt)) {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [
                        ['text' => 'System: '.$systemPrompt],
                    ],
                ];
            }

            // Add context/history
            foreach ($context as $msg) {
                $role = $msg['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = [
                    'role' => $role,
                    'parts' => [
                        ['text' => $msg['content']],
                    ],
                ];
            }

            // Add current user message
            $contents[] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $userMessage],
                ],
            ];

            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2048,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Gemini API error: {$response->status()}");
            }

            $data = $response->json();

            // Extract text from Gemini response
            $text = data_get($data, 'candidates.0.content.parts.0.text', '');

            if (empty($text)) {
                throw new \Exception('Empty response from Gemini API');
            }

            return $text;

        } catch (\Throwable $e) {
            Log::error('Gemini provider error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getProviderName(): string
    {
        return 'gemini';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }
}
