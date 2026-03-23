<?php

namespace App\Enum\Platform;

enum OrderStatusEnum: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    // label() will now return translation keys
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'order.status.draft',
            self::PENDING => 'order.status.pending',
            self::PROCESSING => 'order.status.processing',
            self::COMPLETED => 'order.status.completed',
            self::CANCELED => 'order.status.canceled',
            self::FAILED => 'order.status.failed',
            self::REFUNDED => 'order.status.refunded',
        };
    }

    public function isFinal(): bool
    {
        return match ($this) {
            self::COMPLETED,
            self::CANCELED,
            self::FAILED,
            self::REFUNDED => true,
            default => false,
        };
    }
}
