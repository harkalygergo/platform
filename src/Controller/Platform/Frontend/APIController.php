<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\API\API;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Order;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class APIController extends PlatformController
{
    #[Route('/api/', name: 'api')]
    public function api(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine)
    {
        $request = $requestStack->getCurrentRequest();
        $parameters = $request->request->all();

        // if the honeypot is filled, return error
        if ($parameters['honeypot'] && $parameters['honeypot'] !== '') {
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
                'message' => 'Invalid API key',
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

                // send email
                $fromAddress = $instance->getName() . ' <' . $instance->getOwner()->getEmail() . '>';
                $this->sendMail($toAddresses, $domain. ' új megrendelés: #'. $order->getId(), $emailBody, $fromAddress);

                break;
            }
        }

        return $this->render(
            'platform/frontend/index.html.twig',
            ['content' => 'Siker! Most visszairányítjuk.'],
            $this->redirectAway($HTTP_ORIGIN)
        );
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
