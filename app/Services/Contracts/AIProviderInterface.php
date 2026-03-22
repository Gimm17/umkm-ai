<?php

namespace App\Services\Contracts;

interface AIProviderInterface
{
    /**
     * Generate chat completion
     *
     * @param  array  $context  History messages
     * @return string AI response
     */
    public function generate(string $systemPrompt, string $userMessage, array $context = []): string;

    /**
     * Get provider name
     */
    public function getProviderName(): string;

    /**
     * Check if provider is configured
     */
    public function isConfigured(): bool;
}
