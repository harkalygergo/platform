<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\API\API;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Order;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class APIController extends PlatformController
{
    /*
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
    }
    */

    #[Route('/api/', name: 'api')]
    public function api(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine, SerializerInterface $serializer, HttpClientInterface $httpClient, WebsiteRepository $websiteRepository): Response
    {
        $websites = $websiteRepository->findAll();
        $allowedOrigins = ['https://localhost'];
        foreach($websites as $website){
            $allowedOrigins[] = $website->getDomain();
        }

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials: true");
        }
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        $request = $requestStack->getCurrentRequest();
        $parameters = $request->request->all();

        // if the honeypot is filled, return error
        if (!array_key_exists('honeypot', $parameters) || ($parameters['honeypot'] && $parameters['honeypot'] !== '')) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid request',
            ]);
        }

        $key = $parameters['key'];
        $HTTP_ORIGIN = $request->server->get('HTTP_ORIGIN');
        // cut https:// or http:// from domain
        if (str_starts_with($HTTP_ORIGIN, 'https://')) {
            $domain = substr($HTTP_ORIGIN, 8);
        } elseif (str_starts_with($HTTP_ORIGIN, 'http://')) {
            $domain = substr($HTTP_ORIGIN, 7);
        }

        // find API by domain and key
        $api = $doctrine->getRepository(API::class)->findOneBy([
            'domain' => $domain,
            'publicKey' => $key
        ]);

        if (!$api) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid API key: ' . $key . ", domain: " . $domain,
            ]);
        }

        // check if API is active
        if (!$api->getStatus()) {
            return $this->json([
                'status' => 'error',
                'message' => 'API is inactive',
            ]);
        }

        // check if an instance is valid
        $instance = $doctrine->getRepository(Instance::class)->findOneBy(['id' => $api->getInstance()->getId()]);

        if (!$instance) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid instance',
            ]);
        }

        // check if instance is active
        if (!$api->getInstance()->getStatus()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Instance is inactive',
            ]);
        }

        switch ($parameters['action']) {
            case 'checkout': {
                $shippingMethods = $instance->getShippingMethods();
                $paymentMethods = $instance->getPaymentMethods();

                echo $this->renderView('themes/5_epsilon/checkout.html.twig', [
                    'shippingMethods' => $shippingMethods,
                    'paymentMethods' => $paymentMethods,
                    'cartItems' => json_decode($parameters['cart'], true) ?? '[]',
                    'key' => $parameters['key'],
                ]);

                /*
                $return .= "<h2>Szállítási módok:</h2><ul>";
                foreach ($shippingMethods as $method) {
                    $return .= "<li>".$method->getName() . ': ' . $method->getFee()  . '</li>';
                }
                $return .= "</ul>";

                $return .= "<h2>Fizetési módok:</h2><ul>";
                foreach ($paymentMethods as $method) {
                    $return .= "<li>".$method->getName() . ': ' . $method->getType()  . '</li>';
                }
                $return .= "</ul>";

                echo $return;
                */
                exit();
                break;
            }
            case 'contact': {
                $name = $parameters['name'] ?? null;
                $email = $parameters['email'] ?? null;
                $message = $parameters['message'] ?? null;
                $phone = $parameters['phone'] ?? null;
                $subject = $parameters['subject'] ?? null;
                $toAddresses = [
                    $parameters['email'],
                    $instance->getOwner()->getEmail(),
                ];

                // send email
                $emailBody =  "Név: " . $name . "\n";
                $emailBody .= 'Telefonszám: ' . $phone . "\n";
                $emailBody .= 'E-mail cím: ' . $email . "\n";
                $emailBody .= 'Tárgy: ' . $subject . "\n";
                $emailBody .= 'Üzenet: ' . $message . "\n";
                $fromAddress = $instance->getName() . ' <' . $instance->getOwner()->getEmail() . '>';
                $this->sendMail($toAddresses, $domain. ' új üzenet: '. $subject, $emailBody, $fromAddress);

                break;
            }

            case 'newsletter_subscriber':
            {
                $email = $parameters['email'] ?? null;

                // validate email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->json([
                        'status' => 'error',
                        'message' => 'Invalid email address',
                    ]);
                }

                // save subscriber
                $subscriber = new \App\Entity\Platform\Newsletter\NewsletterSubscriber();
                $subscriber->setName($parameters['name'] ?? null);
                $subscriber->setEmail($email);
                $subscriber->setInstance($instance);
                $subscriber->setCreatedAt(new \DateTimeImmutable());
                $subscriber->setStatus(true);
                $subscriber->setSource($domain);

                // save subscriber
                $em = $doctrine->getManager();
                $em->persist($subscriber);
                $em->flush();

                // send email to domain owner
                $emailBody =  "Név: " . $parameters['name'] . "\n";
                $emailBody .= 'E-mail cím: ' . $email . "\n";
                $fromAddress = $instance->getName() . ' <' . $instance->getOwner()->getEmail() . '>';
                $this->sendMail([$instance->getOwner()->getEmail()], $domain. ' hírlevél feliratkozó: '. $email, $emailBody, $fromAddress);

                /* redirect user instead of json response
                return $this->json([
                    'status' => 'success',
                    'message' => 'Subscription successful',
                ]);
                */

                break;
            }

            case 'order':
            {
                $order = new Order();
                $order->setInstance($instance);
                $order->setComment($parameters['message']);
                $order->setTotal($parameters['quantity']);
                $order->setCreatedAt(new \DateTimeImmutable());
                $order->setPaymentMethod($parameters['paymentMethod']);
                $order->setShippingMethod($parameters['shippingMethod']);
                $order->setFirstName($parameters['firstName']);
                $order->setLastName($parameters['lastName']);
                $order->setPhone($parameters['phone']);
                $order->setEmail($parameters['email']);
                $order->setTotal($parameters['total']);
                $order->setCurrency($parameters['currency']);
                $order->setBillingZip($parameters['billingZip']);
                $order->setBillingCity($parameters['billingCity']);
                $order->setBillingAddress($parameters['billingAddress']);

                if ($parameters['items']) {
                    $order->setItems(explode(',', $parameters['items']));
                }

                // save order
                $em = $doctrine->getManager();
                $em->persist($order);
                $em->flush();

                // send email
                $toAddresses = [
                    $parameters['email'],
                    $instance->getOwner()->getEmail(),
                ];

                $emailBody =  "Rendelés: #" . $order->getId() . "\n";
                $emailBody .= "Név: " . $order->getFirstName() . " " . $order->getLastName() . "\n";
                $emailBody .= 'Telefonszám: ' . $parameters['phone'] . "\n";
                $emailBody .= 'E-mail cím: ' . $parameters['email'] . "\n";
                $emailBody .= 'Számlázás irányítószám: ' . $parameters['billingZip'] . "\n";
                $emailBody .= 'Számlázás település: ' . $parameters['billingCity'] . "\n";
                $emailBody .= 'Számlázás cím: ' . $parameters['billingAddress'] . "\n";
                $emailBody .= 'Mennyiség: ' . $parameters['quantity'] . "\n";
                $emailBody .= 'Fizetési mód: ' . $parameters['paymentMethod'] . "\n";
                $emailBody .= 'Szállítási mód: ' . $parameters['shippingMethod'] . "\n";
                $emailBody .= 'Végösszeg: ' . $parameters['total'] . "\n";
                $emailBody .= 'Megjegyzés: ' . $parameters['message'] . "\n";

                if ($parameters['items']) {
                    $emailBody .= 'Tételek: ' . $parameters['items'] . "\n";
                }

                // send email
                $fromAddress = $instance->getName() . ' <' . $instance->getOwner()->getEmail() . '>';
                $this->sendMail($toAddresses, $domain. ' új megrendelés: #'. $order->getId(), $emailBody, $fromAddress);

                unset($_COOKIE['cart']);
                // initialize Saferpay payment page for Saferpay payment method
                if ($order->getPaymentMethod() === 'Worldline - Novopayment - Saferpay') {
                    return $this->initSaferpayPaymentMethod($order, $key, $httpClient, $HTTP_ORIGIN);

                    exit();
                }

                break;
            }
        }

        return $this->render(
            'platform/frontend/index.html.twig',
            ['content' => 'Siker! Most visszairányítjuk.'],
            $this->redirectAway($HTTP_ORIGIN)
        );
    }









    /* SAFERPAY */

    // initialize Saferpay payment page for Saferpay payment method
    public function initSaferpayPaymentMethod($order, $key, $httpClient, $HTTP_ORIGIN)
    {
        $baseUrl = rtrim($_ENV['SAFERPAY_BASE_URL'] ?? 'https://test.saferpay.com/api', '/');
        $customerId = $_ENV['SAFERPAY_CUSTOMER_ID'] ?? null;
        $terminalId = $_ENV['SAFERPAY_TERMINAL_ID'] ?? null;
        $username = $_ENV['SAFERPAY_USERNAME'] ?? null;
        $password = $_ENV['SAFERPAY_PASSWORD'] ?? null;

        if (!$customerId || !$terminalId || !$username || !$password) {
            return $this->json([
                'status' => 'error',
                'message' => 'Saferpay configuration is missing',
            ], 500);
        }

        $amountValue = (int) round(((float) $order->getTotal()) * 100);
        $currency = $order->getCurrency() ?: 'EUR';

        $successUrl = $this->generateUrl(
            'saferpay_return',
            ['id' => $order->getId(), 'key' => $key, 'status' => 'success', 'HTTP_ORIGIN' => $HTTP_ORIGIN],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $failUrl = $this->generateUrl(
            'saferpay_return',
            ['id' => $order->getId(), 'key' => $key, 'status' => 'fail', 'HTTP_ORIGIN' => $HTTP_ORIGIN],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $abortUrl = $this->generateUrl(
            'saferpay_return',
            ['id' => $order->getId(), 'key' => $key, 'status' => 'abort', 'HTTP_ORIGIN' => $HTTP_ORIGIN],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $notifyUrl = $this->generateUrl(
            'saferpay_notify',
            ['id' => $order->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $initializePayload = [
            'RequestHeader' => [
                'SpecVersion' => '1.19',
                'CustomerId' => $customerId,
                'RequestId' => $order->getId(),
                'RetryIndicator' => 1,
            ],
            'TerminalId' => $terminalId,
            'Payment' => [
                'Amount' => [
                    'Value' => $amountValue,
                    'CurrencyCode' => $currency,
                ],
                'OrderId' => (string) $order->getId(),
                'Description' => 'Order #' . $order->getId(),
            ],
            'ReturnUrls' => [
                'Success' => $successUrl,
                'Fail' => $failUrl,
                'Abort' => $abortUrl,
            ],
            'Notification' => [
                'NotifyUrl' => $notifyUrl,
            ],
            'PaymentMethods' => [
                "VISA", "MASTERCARD",
            ],
        ];

        try {
            $response = $httpClient->request(
                'POST',
                $baseUrl . '/Payment/v1/PaymentPage/Initialize',
                [
                    'auth_basic' => [$username, $password],
                    'json' => $initializePayload,
                ]
            );

            $data = $response->toArray(false);

            if (!isset($data['RedirectUrl'])) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Saferpay initialization failed',
                    'details' => $data,
                ], 502);
            }

            return $this->redirect($data['RedirectUrl']);
        } catch (\Throwable $e) {
            $this->logger->error('Saferpay initialization error', [
                'exception' => $e,
            ]);

            return $this->json([
                'status' => 'error',
                'message' => 'Saferpay initialization error',
            ], 502);
        }
    }

    #[Route('/saferpay/return', name: 'saferpay_return', methods: ['GET'])]
    public function saferpayReturn(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine)
    {
        $request = $requestStack->getCurrentRequest();
        $orderId = $request->query->get('id');
        $status = $request->query->get('status');
        $HTTP_ORIGIN = $request->query->get('HTTP_ORIGIN');

        if ($orderId) {
            $order = $doctrine->getRepository(Order::class)->find($orderId);
            if ($order) {
                // basic mapping of redirect status to internal payment status if notify did not run yet
                if ($status === 'success' && !$order->getPaymentStatus()) {
                    $order->setPaymentStatus('pending_confirmation');
                    $em = $doctrine->getManager();
                    $em->flush();
                }
            }
        }

        return $this->render(
            'platform/frontend/index.html.twig',
            ['content' => '
                <h1>Köszönjük a rendelést! Fizetés feldolgozás alatt.</h1>
                <h2>Hamarosan visszairányítjuk a főoldalra.</h2>
                <script>window.setTimeout(function() { window.location.href = "'.$HTTP_ORIGIN.'"; }, 5000);</script>
            ']
        );
    }

    #[Route('/saferpay/notify', name: 'saferpay_notify', methods: ['GET', 'POST'])]
    public function saferpayNotify(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine, HttpClientInterface $httpClient): Response
    {
        $request = $requestStack->getCurrentRequest();
        $orderId = $request->get('id');
        $token = $request->get('Token');

        if (!$orderId || !$token) {
            return new Response('Missing parameters', 400);
        }

        $order = $doctrine->getRepository(Order::class)->find($orderId);
        if (!$order) {
            return new Response('Order not found', 404);
        }

        $baseUrl = rtrim($_ENV['SAFERPAY_BASE_URL'] ?? 'https://test.saferpay.com/api', '/');
        $customerId = $_ENV['SAFERPAY_CUSTOMER_ID'] ?? null;
        $username = $_ENV['SAFERPAY_USERNAME'] ?? null;
        $password = $_ENV['SAFERPAY_PASSWORD'] ?? null;

        if (!$customerId || !$username || !$password) {
            return new Response('Saferpay configuration is missing', 500);
        }

        $requestHeader = [
            'SpecVersion' => '1.19',
            'CustomerId' => $customerId,
        ];

        try {
            // Assert transaction
            $assertResponse = $httpClient->request(
                'POST',
                $baseUrl . '/Payment/v1/PaymentPage/Assert',
                [
                    'auth_basic' => [$username, $password],
                    'json' => [
                        'RequestHeader' => $requestHeader,
                        'Token' => $token,
                    ],
                ]
            );

            $assertData = $assertResponse->toArray(false);

            $transactionId = $assertData['Transaction']['Id'] ?? null;
            $state = $assertData['Transaction']['Status'] ?? null;
            $liabilityShift = $assertData['Liability']['LiabilityShift'] ?? null;

            if ($transactionId && $state === 'AUTHORIZED' && $liabilityShift === 'YES') {
                // Capture / finalize authorized transaction
                $captureResponse = $httpClient->request(
                    'POST',
                    $baseUrl . '/Payment/v1/Transaction/Capture',
                    [
                        'auth_basic' => [$username, $password],
                        'json' => [
                            'RequestHeader' => $requestHeader,
                            'TransactionReference' => [
                                'TransactionId' => $transactionId,
                            ],
                        ],
                    ]
                );

                $captureData = $captureResponse->toArray(false);
                $captureStatus = $captureData['Status'] ?? null;

                if ($captureStatus === 'CAPTURED') {
                    $order->setPaymentStatus('paid');
                } else {
                    $order->setPaymentStatus('capture_error');
                }
            } else {
                $order->setPaymentStatus('failed');
            }

            $em = $doctrine->getManager();
            $em->flush();

            return new Response('OK', 200);
        } catch (\Throwable $e) {
            $this->logger->error('Saferpay notify error', [
                'exception' => $e,
            ]);

            return new Response('Error', 500);
        }
    }












    public function redirectAway($url)
    {
        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Refresh', '1; url=' . $url);
        $response->send();

        return $response;
    }
}
