<?php

namespace App\Enum\Platform;

enum PaymentMethodTypeEnum: string
{
    public const TYPES = [
        'Bank Transfer' => 'bank_transfer',
        'Cash on Delivery' => 'cash_on_delivery',
        'Card' => 'card',
        'PayPal' => 'paypal',
    ];

    public const CODES = [
        'Worldline - Novopayment - Saferpay' => 'worldline_novopayment_saferpay',
    ];

    public static function getTypeValues(): array
    {
        return array_values(self::TYPES);
    }
}
