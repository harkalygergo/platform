<?php

namespace App\Service\Platform;

use App\Contract\Platform\EmailTokenProviderInterface;

class EmailTemplateRenderer
{
    /**
     * Replaces all [tokens] in both plain text and HTML body.
     */
    public function render(string $content, EmailTokenProviderInterface ...$providers): string
    {
        $tokens = [];
        foreach ($providers as $provider) {
            $tokens = array_merge($tokens, $provider->getTokens());
        }

        return $this->replaceTokens($content, $tokens);
    }

    /**
     * Core replacement — uses strtr() which is faster than str_replace()
     * for many simultaneous substitutions, and does a single pass.
     */
    private function replaceTokens(string $content, array $tokens): array|string
    {
        return strtr($content, $tokens);
    }

    /**
     * Convenience: extract all [token] names found in a template string.
     * Useful for validation or editor hints.
     */
    public function extractTokenNames(string $content): array
    {
        preg_match_all('/\[([a-z_]+)\]/i', $content, $matches);
        return $matches[1] ?? [];
    }
}
