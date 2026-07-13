<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\API\API;
use App\Entity\Platform\CMS\Form;
use App\Entity\Platform\CMS\FormFill;
use App\Entity\Platform\CMS\VisitorLog;
use App\Entity\Platform\Ecom\Product;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Order;
use App\Entity\Platform\Webshop\PaymentMethod;
use App\Entity\Platform\Webshop\ShippingMethod;
use App\Entity\Platform\Website\CmsPage;
use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsiteCategory;
use App\Entity\Platform\Website\WebsitePost;
use App\Enum\Platform\OrderStatusEnum;
use App\Repository\OrderRepository;
use App\Repository\Platform\Webshop\PaymentMethodRepository;
use App\Service\SaferpayService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api')]
class APIController extends PlatformController
{
    protected function corsResponse(Response $response, Request $request): Response
    {
        $origin = $request->headers->get('Origin');
        $websites = $this->doctrine->getRepository(Website::class)->findAll();

        $allowedOrigins = [
            'http://localhost',
            'https://localhost',
            'https://localhost:8000',
        ];

        foreach ($websites as $website) {
            $domain = $website->getDomain();

            $allowedOrigins[] = 'https://' . $domain;
            $allowedOrigins[] = 'http://' . $domain;
        }


        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Vary', 'Origin');

        return $response;
    }

    #[Route('/', name: 'api')]
    public function api(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine, SerializerInterface $serializer, HttpClientInterface $httpClient,  SaferpayService $saferpay, PaymentMethodRepository $paymentMethodRepository): Response
    {
        $request = $requestStack->getCurrentRequest();

        if ($request->getMethod() === 'OPTIONS') {
            return $this->corsResponse(new Response(), $request);
        }

        /*
        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Credentials: true");
        }
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        */

        //$request = $requestStack->getCurrentRequest();
        $parameters = $request->request->all();

        // if the honeypot is filled, return error
        if (!array_key_exists('honeypot', $parameters) || ($parameters['honeypot'] && $parameters['honeypot'] !== '')) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid request',
            ]);
        }

        $domain = '';

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

        $successPageText = '';

        switch ($parameters['action']) {
            case 'form':
            {
                $form = $this->doctrine->getRepository(Form::class)->find($parameters['formID']);

                $toAddresses = [$form->getNotificationEmail()];

                $instanceEmail = $form->getInstance()->getEmail();
                if ($instanceEmail && $instanceEmail !== '' && $instanceEmail !== $form->getNotificationEmail()) {
                    $toAddresses[] = $instanceEmail;
                }

                $emailBody = '';
                $emailHTMLBody = '<table>';
                foreach ($parameters as $parameterKey=>$parameterValue) {
                    $emailHTMLBody .= '<tr>';

                    // exclude formID, key, action, honeypot
                    if (!in_array($parameterKey, ['formID', 'key', 'action', 'honeypot', 'robotstop'])) {

                        if (is_array($parameterValue)) {
                            $parameterValue = implode(', ', $parameterValue);
                        }

                        $emailBody .= $parameterKey . ': ' . $parameterValue . "\n";
                        $emailHTMLBody .= '<td>'.$parameterKey . '</td><th>' . $parameterValue . "</th>";

                        if ($parameterKey === 'email') {
                            $toAddresses[] = $parameterValue;
                        }
                    }
                    $emailHTMLBody .= '</tr>';
                }
                $emailHTMLBody .= '</table>';

                $emailBody .= "\n\n\n".$form->getInstance()->getName()."\n".$request->server->get('HTTP_ORIGIN');
                $emailHTMLBody .= '<br><br><br>'.$form->getInstance()->getName().'<br>'.$request->server->get('HTTP_ORIGIN');

                $fromAddress = $form->getInstance()->getName() . ' <' . $form->getInstance()->getEmail() . '>';

                $this->sendMail($toAddresses,  $form->getName(), $emailBody, $fromAddress, $emailHTMLBody);

                $successPageText = 'Köszönjük! Sikeres űrlap kitöltés.';

                $formFill = new FormFill();
                $formFill->setCreatedBy($instance->getOwner());
                $formFill->setInstance($instance);
                $formFill->setForm($form);
                $formFill->setData($parameters);
                $formFill->setIp($request->getClientIp());
                $em = $doctrine->getManager();
                $em->persist($formFill);
                $em->flush();

                break;
            }

            case 'checkout': {
                //$shippingMethods = $instance->getShippingMethods();
                $shippingMethods = $this->doctrine->getRepository(ShippingMethod::class)->findBy([
                    'instance' => $instance,
                    'status' => true,
                ]);
                //$paymentMethods = $instance->getPaymentMethods();
                $paymentMethods = $this->doctrine->getRepository(PaymentMethod::class)->findBy([
                    'instance' => $instance,
                    'status' => true,
                ]);

                /*
                echo $this->renderView('themes/5_epsilon/checkout.html.twig', [
                    'shippingMethods' => $shippingMethods,
                    'paymentMethods' => $paymentMethods,
                    'cartItems' => json_decode($parameters['cart'], true) ?? '[]',
                    'key' => $parameters['key'],
                ]);
                */

                /**
                 * @var Website $website
                 */
                $referrer = str_replace(['https://', 'http://', '/'], '', $request->server->get('HTTP_REFERER'));

                $website = $this->doctrine->getRepository(Website::class)->findOneBy(
                    [
                        'domain' => $referrer,
                    ]
                );

                return $this->corsResponse(
                    new Response($this->renderView('themes/'.$website->getTemplate()->getPosition().'_'.$website->getTemplate()->getCode().'/checkout.html.twig', [
                        'website' => $instance->getWebsites()->first(),
                        'shippingMethods' => $shippingMethods,
                        'paymentMethods' => $paymentMethods,
                        'cartItems' => json_decode($parameters['cart'], true) ?? [],
                        'key' => $parameters['key'],
                    ])),
                    $request
                );


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

                $successPageText = 'Köszönjük! Sikeres üzenetküldés.';

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
                $successPageText = 'Köszönjük! Sikeres feliratkozás.';

                break;
            }

            case 'order':
            {
                /**
                 * @var PaymentMethod $paymentMethod
                 */
                $paymentMethod = $paymentMethodRepository->find($parameters['paymentMethod']);

                $order = new Order();
                $order->setInstance($instance);
                $order->setComment($parameters['message']);
                $order->setTotal($parameters['quantity']);
                $order->setCreatedAt(new \DateTimeImmutable());
                $order->setPaymentMethod($paymentMethod);
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
                $order->setPaymentToken(uniqid());
                $order->setStatus(OrderStatusEnum::PENDING);

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
                $emailBody .= 'Fizetési mód: ' . $paymentMethod->getName() . "\n";
                $emailBody .= 'Szállítási mód: ' . $parameters['shippingMethod'] . "\n";
                $emailBody .= 'Végösszeg: ' . $parameters['total'] . "\n";
                $emailBody .= 'Megjegyzés: ' . $parameters['message'] . "\n";

                if ($parameters['items']) {
                    $emailBody .= 'Tételek: ' . $parameters['items'] . "\n";
                }

                // send email
                $fromEmail = $instance->getEmail() ?? $instance->getOwner()->getEmail();
                $fromAddress = $instance->getName() . ' <' . $fromEmail . '>';
                $this->sendMail($toAddresses, $domain. ' #'. $order->getId(). " | ".$order->getFirstName() . " " . $order->getLastName(), $emailBody, $fromAddress);

                unset($_COOKIE['cart']);
                $successPageText = 'Köszönjük! Sikeres rendelés: #'.$order->getId().'';
                // initialize Saferpay payment page for Saferpay payment method
                if ($paymentMethod->getCode() === 'worldline_novopayment_saferpay') {
                    $order->setPaymentToken(uniqid());
                    $order->setStatus(OrderStatusEnum::PROCESSING);

                    //return $this->initSaferpayPaymentMethod($order, $key, $httpClient, $HTTP_ORIGIN);
                    //dump($HTTP_ORIGIN);

                    //$HTTP_ORIGIN = $request->getSchemeAndHttpHost();
                    //dd($HTTP_ORIGIN);

                    try {
                        $result = $saferpay->initSaferpayPaymentMethod(
                            $order,
                            $paymentMethod,
                            'card',
                            $httpClient,
                            $HTTP_ORIGIN
                        );
                        $order->setPaymentStatus('processing');
                        if (isset($result['redirectUrl'])) {
                            return $this->redirect($result['redirectUrl']);
                        } else {
                            $successPageText = json_encode($result);
                        }
                    } catch (\Exception $e) {
                        $order->setPaymentStatus('failed');
                        throw new \Exception('ERROR! Saferpay init failed: '.$e->getMessage());
                    } finally {
                        $em->persist($order);
                        $em->flush();
                    }
                }
                break;
            }
        }

        return $this->render('platform/frontend/index.html.twig', [
            'content' => '<h1>'.$successPageText.'</h1><h2>Hamarosan visszairányítjuk a főoldalra.</h2>',
            'redirect_url' => $HTTP_ORIGIN,
            'redirect_delay' => 5,
        ]);
    }

    private function isBot(string $userAgent): bool
    {
        $botKeywords = [
            'bot', 'crawler', 'spider', 'slurp',   // általános
            'Google', 'Bing', 'Yahoo', 'Yandex',    // keresők
            'Baidu', 'DuckDuck', 'Sogou',           // egyéb keresők
            'facebookexternalhit', 'Twitterbot',    // social
            'LinkedInBot', 'WhatsApp', 'Telegram',  // social
            'Applebot', 'Amazonbot',                // egyéb big tech
            'Bytespider',       // ByteDance
            'GPTBot',           // OpenAI
            'Claude-Web',       // Anthropic
            'ClaudeBot',        // Anthropic
            'CCBot',            // Common Crawl (AI training adatbázis)
            'YouBot',           // You.com
            'PerplexityBot',    // Perplexity AI
            'cohere-ai',        // Cohere
            'anthropic-ai',     // Anthropic
            'omgili',           // Webhose / adatgyűjtő
            'DataForSeoBot',    // SEO tool crawler
        ];

        return (bool) array_filter(
            $botKeywords,
            fn($keyword) => stripos($userAgent, $keyword) !== false
        );
    }

    #[Route('/log/visitor/', name: 'api_log_visitor')]
    public function apiLogVisitor(Request $request)
    {
        $return = '';

        $parameters = $request->request->all();
        $visitorIP = $this->requestStack->getCurrentRequest()->getClientIp();
        $developerIPsFromDotenv = explode(',', $_ENV['DEVELOPER_IP'] ?? getenv('DEVELOPER_IP'));

        if (empty($parameters['host'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Missing host parameter',
            ]);
        }

        $website = $this->doctrine->getRepository(Website::class)->findOneBy(
            [
                'domain' => $parameters['host']
            ]
        );

        if ($website && !in_array($visitorIP, $developerIPsFromDotenv)) {
            /**
             * @var Website $website
             */
            if ( str_contains($request->server->get('HTTP_REFERER'), $parameters['host'])) {
                $userAgent = $parameters['user_agent'] ?? $request->server->get('HTTP_USER_AGENT');

                if (!$this->isBot($userAgent)) {
                    $visitorLog = new VisitorLog();

                    $visitorLog->setVisitedAt(new \DateTimeImmutable());
                    $visitorLog->setUrl($parameters['url']);
                    if ($parameters['referrer'] !== "null"){
                        $visitorLog->setReferrer($parameters['referrer']);
                    }
                    $visitorLog->setContentType($parameters['content_type']);
                    $visitorLog->setContentId($parameters['id']);
                    $visitorLog->setUserAgent($userAgent);
                    $visitorLog->setIpAddress($visitorIP);
                    $visitorLog->setSessionId($this->requestStack->getSession()->getId());
                    $visitorLog->setInstance($this->doctrine->getRepository(Instance::class)->find((int)$parameters['instance']));

                    switch ($parameters['content_type']) {
                        case 'App\Entity\Platform\Ecom\Product':
                        {
                            $entity = $this->doctrine->getRepository(Product::class)->find((int)$parameters['id']);
                            if ($entity) {
                                $entity->incrementViewCount();
                                $this->doctrine->getManager()->flush();
                            }
                            break;
                        }
                        case 'App\Entity\Platform\Website\CmsPage':
                        {
                            $entity = $this->doctrine->getRepository(CmsPage::class)->find((int)$parameters['id']);
                            if ($entity) {
                                $entity->incrementViewCount();
                                $this->doctrine->getManager()->flush();
                            }
                            break;
                        }
                        case 'App\Entity\Platform\Website\WebsiteCategory':
                        {
                            $entity = $this->doctrine->getRepository(WebsiteCategory::class)->find((int)$parameters['id']);
                            if ($entity) {
                                $entity->incrementViewCount();
                                $this->doctrine->getManager()->flush();
                            }
                            break;
                        }
                        case 'App\Entity\Platform\Website\WebsitePost':
                        {
                            $entity = $this->doctrine->getRepository(WebsitePost::class)->find((int)$parameters['id']);
                            if ($entity) {
                                $entity->incrementViewCount();
                                $this->doctrine->getManager()->flush();
                            }
                            break;
                        }
                    }

                    $this->doctrine->getManager()->persist($visitorLog);
                    $this->doctrine->getManager()->flush();
                }
            }
        }

        return $this->corsResponse(
            new Response($return),
            $request
        );
    }


    /* SAFERPAY */
    #[Route('/payment/success', name: 'payment_success', methods: ['POST'])]
    public function success(EntityManagerInterface $em, Request $request, SaferpayService $service, OrderRepository $repo, HttpClientInterface $httpClient): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['Token'] ?? null;

        $order = $repo->findOneBy(['saferpayToken' => $token]);
        if (!$order) {
            return new Response('Order not found', 404);
        }

        $service->handleNotify($order, true, $httpClient);
        $em->flush();

        // flush entity manager here
        return new Response('OK', 200);
    }

    #[Route('/payment/fail', name: 'payment_fail', methods: ['POST'])]
    public function fail(EntityManagerInterface $em, Request $request, SaferpayService $service, OrderRepository $repo, HttpClientInterface $httpClient): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['Token'] ?? null;

        $order = $repo->findOneBy(['saferpayToken' => $token]);
        if (!$order) {
            return new Response('Order not found', 404);
        }

        $service->handleNotify($order, false, $httpClient);
        $em->flush();

        // flush entity manager here
        return new Response('OK', 200);
    }













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
                'SpecVersion' => '1.51',
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
                'SuccessNotifyUrl' => $notifyUrl,
                'FailNotifyUrl' => $notifyUrl,
            ],
            'PaymentMethods' => [
                "VISA", "MASTERCARD",
            ],
        ];

        $initializePayload = [
            'RequestHeader' => [
                'SpecVersion'   => '1.51',
                'CustomerId'    => $customerId,
                'RequestId'     => uniqid('req_', true),
                'RetryIndicator'=> 0,
                'ClientInfo'    => [
                    'ShopInfo' => 'My Shop',
                ],
            ],

            'TerminalId' => $terminalId,

            'PaymentMethods' => ['VISA', 'MASTERCARD'],

            'Payment' => [
                'Amount' => [
                    'Value' => $amountValue,
                    'CurrencyCode' => $currency,
                ],
                'OrderId' => (string) $order->getId(),
                'Description' => 'Order #' . $order->getId(),
            ],

            'Payer' => [
                'IpAddress'   => $_SERVER['REMOTE_ADDR'],
                'LanguageCode'=> 'hu',
            ],

            // user redirect (frontend)
            'ReturnUrl' => [
                'Url' => $notifyUrl, //'https://yourdomain.com/payment/return?orderId=' . $orderId,
            ],

            // server-side tracking (REQUIRED for success/fail storage)
            'Notification' => [
                'SuccessNotifyUrl' => $successUrl,
                'FailNotifyUrl' => $failUrl,
            ],

            /*
            // optional but recommended
            'BillingAddressForm' => [
                'AddressSource' => 'SAFERPAY',
            ],
            */
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
                if ($status === "SUCCESS") {
                    $em = $doctrine->getManager();
                    $em->flush();

                    $successPageText = 'Köszönjük a rendelést: #'. $order->getId().'. Fizetés sikeres, rendelés feldolgozás alatt.';

                    return $this->render('platform/frontend/index.html.twig', [
                        'content' => '<h1>'.$successPageText.'</h1><h2>Hamarosan visszairányítjuk a főoldalra.</h2>',
                        'redirect_url' => $HTTP_ORIGIN,
                        'redirect_delay' => 5,
                    ]);
                }
            }
        }

        $successPageText = 'Köszönjük a rendelést! Fizetés sikertelen.';

        return $this->render('platform/frontend/index.html.twig', [
            'content' => '<h1>'.$successPageText.'</h1><h2>Hamarosan visszairányítjuk a főoldalra.</h2>',
            'redirect_url' => $HTTP_ORIGIN,
            'redirect_delay' => 5,
        ]);
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
}
