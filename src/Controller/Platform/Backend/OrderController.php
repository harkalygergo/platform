<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\BillingProfile;
use App\Entity\Platform\Order;
use App\Entity\Platform\Service;
use App\Entity\Platform\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted(User::ROLE_USER)]
class OrderController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/orders', name: 'admin_v1_orders')]
    public function index(Request $request): Response
    {
        $orders = $this->doctrine->getRepository(Order::class)->findAll();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelések',
            'tableHead' => [
                'createdAt' => 'Rendelés dátuma',
                'createdBy' => 'Rendelő',
                'instance' => 'Intézmény',
                'total' => 'Összeg',
                'currency' => 'Pénznem',
                'items' => 'Tételek',
                'billingProfile' => 'Számlázási profil',
                'paymentStatus' => 'Fizetési státusz',
            ],
            'tableBody' => $orders,
            'actions' => [
                'view',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/{_locale}/admin/v1/orders/view/{id}', name: 'admin_v1_orders_view')]
    public function view(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/view.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés megtekintése',
            'order' => $order,
        ]);
    }

    #[Route('/{_locale}/admin/v1/orders/edit/{id}', name: 'admin_v1_orders_edit')]
    public function edit(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/edit.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés szerkesztése',
            'order' => $order,
        ]);
    }

    #[Route('/{_locale}/admin/v1/orders/delete/{id}', name: 'admin_v1_orders_delete')]
    public function delete(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/delete.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés törlése',
            'order' => $order,
        ]);
    }

    #[Route('/{_locale}/admin/v1/orders/create', name: 'admin_v1_orders_create')]
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        // get billing profile object based on posted integer id
        $billingProfile = $this->doctrine->getRepository(BillingProfile::class)->find($request->request->get('billingProfile'));

        $order = new Order();
        $order->setBillingProfile($billingProfile);
        $order->setPaymentStatus('fizetésre vár');
        $order->setCurrency('HUF');
        $order->setTotal($request->request->get('total'));
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setCreatedBy($this->getUser());
        $order->setInstance($this->currentInstance);
        $order->setItems($this->getUser()->getCart()->getItems());
        $order->setComment($request->request->get('comment'));

        $this->doctrine->getManager()->persist($order);
        $this->doctrine->getManager()->flush();

        $emailBody = "Rendelés azonosító: #" . $order->getId() . "\n";
        $emailBody .= "Név: " . $order->getCreatedBy()->getFullName() . "\n";
        $emailBody .= "Szervezet: " . $order->getInstance()->getName() . "\n";
        $emailBody .= "Összeg: " . $order->getTotal() . "\n";
        $emailBody .= "Fizetési mód: " . $request->request->get('paymentMethod') . "\n";

        $billingProfileId = $request->request->get('billingProfile');
        $billingProfile = $this->doctrine->getRepository(BillingProfile::class)->find($billingProfileId);
        $emailBody .= "Számlázási profil: " . $billingProfile->getName() . "\n";
        $emailBody .= "Megjegyzés: " . $order->getComment() . "\n";
        $emailBody .= "\n Tételek: \n";

        foreach ($order->getItems() as $item) {
            $service = $this->doctrine->getRepository(Service::class)->find($item);
            $emailBody .= $service->getName() . " - " . $service->getType() . " - " . $service->getFee() . " " . $service->getCurrency() . "\n";
        }

        //$emailBody .= $serializer->serialize($order->getItems(), 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['cart']]);

        $toAddresses = [
            // logged in user email
            $this->getUser()->getEmail(),
        ];

        $this->sendMail($toAddresses, 'Új megrendelés: #'. $order->getId(), $emailBody);

        // empty the cart
        $cart = $this->getUser()->getCart();
        $cart->setItems([]);
        $this->doctrine->getManager()->persist($cart);
        $this->doctrine->getManager()->flush();

        // return with new order ID
        return new Response($order->getId());
    }
}
