<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum NewsletterStatusEnum: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case SENT = 'sent';

    public function label(): string
    {
        return $this->toString();
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::SCHEDULED => 'primary',
            self::SENT => 'success',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DRAFT => 'fa-solid fa-file',
            self::SCHEDULED => 'fa-solid fa-clock',
            self::SENT => 'fa-solid fa-check',
        };
    }

    public function iconColor(): string
    {
        return match($this) {
            self::DRAFT => 'text-secondary',
            self::SCHEDULED => 'text-warning',
            self::SENT => 'text-success',
        };
    }

    public function iconColorInverse(): string
    {
        return match($this) {
            self::DRAFT => 'text-white',
            self::SCHEDULED => 'text-white',
            self::SENT => 'text-white',
        };
    }

    public function iconColorInverseBackground(): string
    {
        return match($this) {
            self::DRAFT => 'bg-secondary',
            self::SCHEDULED => 'bg-warning',
            self::SENT => 'bg-success',
        };
    }

    public function toString(): string
    {
        return match($this) {
            self::DRAFT => 'newsletter.draft',
            self::SCHEDULED => 'newsletter.scheduled',
            self::SENT => 'newsletter.sent',
        };
    }
}
