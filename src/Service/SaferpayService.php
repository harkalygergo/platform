<?php

namespace App\Service;

use App\Entity\Platform\Webshop\PaymentMethod;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SaferpayService
{
    private ?string $customerId = null;
    private ?string $terminalId = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $apiBase = null;

    public function initializeVariables(PaymentMethod $paymentMethod): void
    {
        if ($paymentMethod->getCardStatus()) {
            $this->setApiBase($paymentMethod->getCardBaseUrlLive());
            $this->setCustomerId($paymentMethod->getCardCustomerLive());
            $this->setTerminalId($paymentMethod->getCardTerminalLive());
            $this->setUsername($paymentMethod->getCardUsernameLive());
            $this->setPassword($paymentMethod->getCardPasswordLive());
        } else {
            $this->setApiBase($paymentMethod->getCardBaseUrlTest());
            $this->setCustomerId($paymentMethod->getCardCustomerTest());
            $this->setTerminalId($paymentMethod->getCardTerminalTest());
            $this->setUsername($paymentMethod->getCardUsernameTest());
            $this->setPassword($paymentMethod->getCardPasswordTest());
        }
    }

    public function initSaferpayPaymentMethod(object $order, PaymentMethod $paymentMethod, string $key, HttpClientInterface $httpClient, string $HTTP_ORIGIN): array
    {
        $this->initializeVariables($paymentMethod);

        $payload = [
            'RequestHeader' => [
                'SpecVersion'    => '1.51',
                'CustomerId'     => $this->customerId,
                'RequestId'      => uniqid('req_', true),
                'RetryIndicator' => 0,
                'ClientInfo'     => ['ShopInfo' => $HTTP_ORIGIN],
            ],
            'TerminalId' => $this->terminalId,
            'Payment' => [
                'Amount' => [
                    'Value'        => (int) round(((float) $order->getTotal()) * 100), // cents
                    'CurrencyCode' => $order->getCurrency() ?: 'EUR',
                ],
                'OrderId'     => (string)$order->getId(),
                'Description' => 'Order #' . $order->getId(),
            ],
            'ReturnUrl' => [
                'Url' => $_ENV['APP_URL'] . '/payment/return/'.preg_replace( "#^[^:/.]*[:/]+#i", "", $HTTP_ORIGIN ).'/'.$order->getId().'/'.$order->getPaymentToken(),
            ],
            'Notification' => [
                'SuccessNotifyUrl' => $_ENV['APP_URL'] . '/api/payment/success',
                'FailNotifyUrl'    => $_ENV['APP_URL'] . '/api/payment/fail',
            ],
        ];

        $response = $httpClient->request('POST', $this->apiBase . '/Payment/v1/PaymentPage/Initialize', [
            'auth_basic' => [$this->username, $this->password],
            'json'       => $payload,
        ]);

        $data = $response->toArray(false);

        if ($response->getStatusCode() !== 200 || isset($data['ErrorName'])) {
            $order->setPaymentStatus('FAILED');
            return ['error' => $data];
        }

        // store token for later Assert
        $order->setPaymentStatus('PENDING');
        $order->setPaymentToken($data['Token'] ?? null);

        return [
            'redirectUrl' => $data['RedirectUrl'],
            'token'       => $data['Token'],
        ];
    }

    public function handleNotify(object $order, bool $success, HttpClientInterface $httpClient): void
    {
        if (!$success) {
            $order->setPaymentStatus('FAILED');
            return;
        }

        $paymentMethod = $order->getPaymentMethod();
        $this->initializeVariables($paymentMethod);

        // verify with Assert
        $response = $httpClient->request('POST', $this->getApiBase() . '/Payment/v1/PaymentPage/Assert', [
            'auth_basic' => [$this->getUsername(), $this->getPassword()],
            'json' => [
                'RequestHeader' => [
                    'SpecVersion'    => '1.51',
                    'CustomerId'     => $this->customerId,
                    'RequestId'      => uniqid('req_', true),
                    'RetryIndicator' => 0,
                ],
                'Token' => $order->getPaymentToken(),
            ],
        ]);

        $data = $response->toArray(false);

        if (
            $response->getStatusCode() === 200 &&
            ($data['Transaction']['Status'] ?? null) === 'AUTHORIZED'
        ) {
            $order->setPaymentStatus('SUCCESS');
        } else {
            $order->setPaymentStatus('FAILED: '.$data['ErrorMessage']);
        }
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getTerminalId(): ?string
    {
        return $this->terminalId;
    }

    public function setTerminalId(?string $terminalId): void
    {
        $this->terminalId = $terminalId;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getApiBase(): ?string
    {
        return $this->apiBase;
    }

    public function setApiBase(?string $apiBase): void
    {
        $this->apiBase = rtrim($apiBase, '/');
    }
}
