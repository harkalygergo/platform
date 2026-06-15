<?php

namespace App\Email\Tokens\Platform;

use App\Contract\Platform\EmailTokenProviderInterface;
use App\Entity\Platform\Order;

class OrderEmailTokens implements EmailTokenProviderInterface
{
    public function __construct(private readonly Order $order) {}

    public function getTokens(): array
    {
        return [
            '[order___number]'       => (string) $this->order->getId(),
            '[order___created_at]'         => $this->order->getCreatedAt()->format('Y-m-d'),
            '[order___total]'        => number_format($this->order->getTotal(), 2),
            '[order__shipping_address]' => $this->order->getShippingAddress(),
            '[order__currency]' => $this->order->getCurrency(),
        ];
    }
}
