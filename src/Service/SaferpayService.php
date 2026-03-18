<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SaferpayService
{
    private string $customerId;
    private string $terminalId;
    private string $username;
    private string $password;
    private string $apiBase;

    public function __construct()
    {
        $this->customerId = $_ENV['SAFERPAY_CUSTOMER_ID'] ?? null;
        $this->terminalId = $_ENV['SAFERPAY_TERMINAL_ID'] ?? null;
        $this->username   = $_ENV['SAFERPAY_USERNAME'] ?? null;
        $this->password   = $_ENV['SAFERPAY_PASSWORD'] ?? null;
        $this->apiBase    = rtrim($_ENV['SAFERPAY_BASE_URL'] ?? 'https://test.saferpay.com/api', '/'); // prod: https://www.saferpay.com/api
    }

    public function initSaferpayPaymentMethod(object $order, string $key, HttpClientInterface $httpClient, string $HTTP_ORIGIN): array
    {
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

        // verify with Assert
        $response = $httpClient->request('POST', $this->apiBase . '/Payment/v1/PaymentPage/Assert', [
            'auth_basic' => [$this->username, $this->password],
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
}
