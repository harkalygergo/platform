<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\BillingProfile;
use App\Entity\Platform\Cart;
use App\Entity\Platform\Service;
use App\Entity\Platform\User;
use App\Repository\Platform\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class CheckoutController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/checkout', name: 'admin_v1_checkout')]
    public function index(Request $request): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();
        $instances = $user->getInstances();
        $billingProfiles = $this->doctrine->getRepository(BillingProfile::class)->findByUserInstances($instances);

        $cart = $this->doctrine->getRepository(Cart::class)->findOneBy(['user' => $this->getUser()]);

        if (!$cart) {
            $cart = (new Cart())->setUser($this->getUser());
        }

        $items = $cart->getItems();

        $cartServices = [];
        $feeSum = 0;

        if (!is_null($items)) {
            foreach ($items as $item) {
                $service = $this->doctrine->getRepository(Service::class)->find($item);
                $cartServices[] = $service;
                $feeSum += $service->getFee();
            }
        }

        $instance = $this->currentInstance;
        $services = (new ServiceRepository($this->doctrine))->findBy(['instance' => $instance]);

        return $this->render('platform/backend/v1/checkout.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Pénztár',
            'tableHead' => [
                'name' => 'Megnevezés',
                'description' => $this->translator->trans('data.description'),
                'type' => 'Típus',
                'fee' => 'Díj',
                'currency' => 'Pénznem',
                'frequencyOfPayment' => 'Fizetési gyakoriság',
                //'nextPaymentDate' => 'Következő fizetési dátum',
                'status' => 'Státusz',
            ],
            //'tableBody' => (new ServiceRepository($this->doctrine))->findAll(),
            'tableBody' => $cartServices,
            'actions' => [
                'view',
                'edit',
                'delete',
            ],
            'feeSum' => $feeSum,
            'billingProfiles' => $billingProfiles,
            'services' => $services,
        ]);
    }
}
