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
            '[order__shipping_address]' => $this->order->getShippingAddress(),

            '[order___created_at]' => $this->order->getCreatedAt()->format('Y-m-d'),
            '[order___total]' => number_format($this->order->getTotal(), 2),
            '[order__currency]' => $this->order->getCurrency(),
            '[order__first_name]' => $this->order->getFirstName(),
            '[order__last_name]' => $this->order->getLastName(),
            '[order__phone]' => $this->order->getPhone(),
            '[order__email]' => $this->order->getEmail(),
            '[order__billing_country]' => $this->order->getBillingCountry(),
            '[order__billing_zip]' => $this->order->getBillingZip(),
            '[order__billing_city]' => $this->order->getBillingCity(),
            '[order__shipping_method]' => $this->order->getShippingMethod(),
            '[order__payment_status]' => $this->order->getPaymentStatus(),
            //'[order__status]' => $this->order->getStatus(),
        ];
    }
}
