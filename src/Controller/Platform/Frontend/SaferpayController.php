<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Order;
use App\Repository\OrderRepository;
use App\Service\SaferpayService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SaferpayController extends PlatformController
{
    #[Route('/payment/return/{HTTP_ORIGIN}/{order}/{orderToken}', name: 'payment_return')]
    public function return(EntityManagerInterface $em, Request $request, OrderRepository $repo, SaferpayService $service, HttpClientInterface $httpClient, string $HTTP_ORIGIN, Order $order, string $orderToken): Response
    {
        if (!$order) {
            return new Response('Order not found', 404);
        }

        // call Assert to check final status
        $service->handleNotify($order, true, $httpClient);

        $status = $order->getPaymentStatus();
        $em->flush();

        $HTTP_ORIGIN = 'https://' .$HTTP_ORIGIN;

        return match($status) {
            'SUCCESS' => $this->redirectToRoute('saferpay_return', ['id' => $order->getId(), 'status' => $status, 'HTTP_ORIGIN' => $HTTP_ORIGIN]),
            'FAILED'  => $this->redirectToRoute('saferpay_return',  ['id' => $order->getId(), 'status' => $status, 'HTTP_ORIGIN' => $HTTP_ORIGIN]),
            'CANCELED'=> $this->redirectToRoute('saferpay_return',['id' => $order->getId(), 'status' => $status, 'HTTP_ORIGIN' => $HTTP_ORIGIN]),
            default   => $this->redirectToRoute('saferpay_return',  ['id' => $order->getId(), 'status' => $status, 'HTTP_ORIGIN' => $HTTP_ORIGIN]),
        };
    }
}
