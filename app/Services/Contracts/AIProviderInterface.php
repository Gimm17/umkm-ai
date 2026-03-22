<?php

namespace App\Services\Contracts;

interface AIProviderInterface
{
    /**
     * Generate chat completion
     *
     * @param string $systemPrompt
     * @param string $userMessage
     * @param array $context History messages
     * @return string AI response
     */
    public function generate(string $systemPrompt, string $userMessage, array $context = []): string;

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string;

    /**
     * Check if provider is configured
     *
     * @return bool
     */
    public function isConfigured(): bool;
}
