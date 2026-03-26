<?php

namespace App\Services\AI\Providers;

interface AiProviderInterface
{
    /**
     * Generate content from a prompt
     */
    public function generate(string $prompt, array $options = []): array;

    /**
     * Check if the provider is available
     */
    public function isAvailable(): bool;

    /**
     * Get provider name
     */
    public function getName(): string;
}
