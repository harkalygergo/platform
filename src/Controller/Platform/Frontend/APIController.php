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

        switch ($parameters['action']) {
            case 'order':
            {
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

                // check if instance is valid
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
                $this->sendMail($toAddresses, $domain. ' új megrendelés: #'. $order->getId(), $emailBody);

                // return to /
                return $this->render(
                    'platform/frontend/index.html.twig',
                    ['body' => 'Sikeres rendelés, hamarosan visszairányítjuk a webáruházba.'],
                    $this->redirectAway($HTTP_ORIGIN)
                );

                /*
                $response = new Response();
                $response->headers->set('Location', $HTTP_ORIGIN);
                $response->setStatusCode(302);
                $response->send();
                return $response;
                */

                break;
            }
        }



        dump($parameters);

        // get HTTP_ORIGIN

        dump($request);
        // get request body
        $body = $request->getContent();
        // content is a string, like parameter=value&parameter2=value2

        dump($body);
        // decode json
        $data = json_decode($body, true);
        dd($data);
        // check if json is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON',
            ]);
        }
        // check if request is valid
        if (!isset($data['action'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid request',
            ]);
        }
        // check if action is valid
        if (!in_array($data['action'], ['get', 'post', 'put', 'delete'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid action',
            ]);
        }
        // check if instance is valid
        if (!isset($data['instance'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid instance',
            ]);
        }
        // check if instance is valid
        $instance = $this->doctrine->getRepository(Instance::class)->findOneBy(['id' => $data['instance']]);
        if (!$instance) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid instance',
            ]);
        }


        dd('API');
    }

    public function redirectAway($url)
    {
        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Refresh', '5; url=' . $url);
        $response->send();

        return $response;
    }
}
