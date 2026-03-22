<?php

namespace App\Services;

use App\Services\Contracts\AIProviderInterface;
use App\Services\AIProviders\GeminiProvider;
use App\Services\AIProviders\KimiProvider;
use App\Services\AIProviders\GLMProvider;
use Illuminate\Support\Facades\Log;

class AIProviderFactory
{
    /**
     * Get AI provider instance based on configuration
     */
    public static function getProvider(): AIProviderInterface
    {
        $provider = config('services.ai.provider', 'gemini');

        return match($provider) {
            'gemini' => new GeminiProvider(),
            'kimi' => new KimiProvider(),
            'glm' => new GLMProvider(),
            'anthropic' => self::getAnthropicProvider(),
            default => self::getFirstAvailableProvider(),
        };
    }

    /**
     * Get first available provider that is configured
     */
    private static function getFirstAvailableProvider(): AIProviderInterface
    {
        $providers = [
            'gemini' => new GeminiProvider(),
            'kimi' => new KimiProvider(),
            'glm' => new GLMProvider(),
        ];

        foreach ($providers as $name => $provider) {
            if ($provider->isConfigured()) {
                Log::info("Using AI provider: {$name}");
                return $provider;
            }
        }

        throw new \Exception('No AI provider is configured. Please set API key for at least one provider (gemini, kimi, or glm).');
    }

    /**
     * Get Anthropic provider (if available)
     */
    private static function getAnthropicProvider(): AIProviderInterface
    {
        // For now, return first available if Anthropic is selected
        return self::getFirstAvailableProvider();
    }

    /**
     * Get list of available providers
     */
    public static function getAvailableProviders(): array
    {
        $providers = [
            'gemini' => new GeminiProvider(),
            'kimi' => new KimiProvider(),
            'glm' => new GLMProvider(),
        ];

        $available = [];
        foreach ($providers as $name => $provider) {
            $available[$name] = $provider->isConfigured();
        }

        return $available;
    }
}
