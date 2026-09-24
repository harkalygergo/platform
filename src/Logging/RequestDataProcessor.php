<?php

namespace App\Logging;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsMonologProcessor()]
final class RequestDataProcessor
{
    private const SENSITIVE_KEYS = [
        'password',
        'plainPassword',
        '_password',
        'token',
        'secret',
        'credit_card',
        'authorization',
    ];

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return $record;
        }

        // Add GET and POST data (with sensitive fields masked)
        $record->extra['http_url'] = $request->getUri();
        $record->extra['http_method'] = $request->getMethod();
        $record->extra['client_ip'] = $request->getClientIp();
        $record->extra['get_params'] = $request->query->all();
        $record->extra['post_params'] = $this->sanitize($request->request->all());

        return $record;
    }

    /**
     * Recursively masks sensitive fields such as passwords and tokens.
     */
    private function sanitize(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitize($value);
            } elseif (in_array(strtolower((string) $key), self::SENSITIVE_KEYS, true)) {
                $data[$key] = '******';
            }
        }

        return $data;
    }
}
