<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\BillingProfile;
use App\Entity\Platform\Order;
use App\Entity\Platform\Service;
use App\Entity\Platform\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted(User::ROLE_USER)]
#[\Symfony\Component\Routing\Attribute\Route('/{_locale}/admin/v1/order')]
class OrderController extends PlatformController
{
    #[Route('/', name: 'admin_v1_order_index')]
    public function index(Request $request): Response
    {
        $orders = $this->doctrine->getRepository(Order::class)->findBy([
            'instance' => $this->currentInstance,
        ]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelések',
            'tableHead' => [
                'id' => 'Azonosító',
                'createdAt' => 'Dátum',
                'lastName' => 'Vezetéknév',
                'firstName' => 'Keresztnév',
                'phone' => 'Telefonszám',
                'email' => 'E-mail',
                'shippingMethod' => 'Szállítási mód',
                'shippingAddress' => 'Szállítási cím',
                'paymentMethod' => 'Fizetési mód',
                'paymentStatus' => 'Fizetési státusz',
                'comment' => 'Megjegyzés',
                'total' => 'Összeg',
                'currency' => 'Pénznem',
                'billingZip' => 'Számlázás irányítószám',
                'billingCity' => 'Számlázás település',
                'billingAddress' => 'Számlázás cím',
                'items' => 'Tételek',
            ],
            'tableBody' => $orders,
            'actions' => [
                'delete'
            ],
        ]);
    }

    #[Route('/view/{id}', name: 'admin_v1_order_view')]
    public function view(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/view.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés megtekintése',
            'order' => $order,
        ]);
    }

    #[Route('/edit/{id}/', name: 'admin_v1_order_edit')]
    public function edit(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/edit.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés szerkesztése',
            'order' => $order,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_v1_order_delete')]
    public function delete(Request $request, Order $id): Response
    {
        // check if order instance matches current instance
        if ($id->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('You do not have permission'));
            return $this->redirectToRoute('admin_v1_order_index');
            //throw $this->createAccessDeniedException($this->translator->trans('You do not have permission'));
        }

        // check if order exists
        if (!$id) {
            $this->addFlash('error', 'Rendelés nem található.');
            return $this->redirectToRoute('admin_v1_order_index');
        }

        // remove order
        $this->doctrine->getManager()->remove($id);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_order_index');
    }

    #[Route('/multiple/{action}/{ids}', name: 'admin_v1_order_multiple')]
    public function multiple(Request $request, string $action, string $ids): Response
    {
        $idsArray = explode(',', $ids);

        switch ($action) {
            case 'delete':
                foreach ($idsArray as $id) {
                    $order = $this->doctrine->getRepository(Order::class)->find($id);
                    if ($order && $order->getInstance() === $this->currentInstance) {
                        $this->doctrine->getManager()->remove($order);
                    }
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid action');
        }

        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_order_index');
    }

    #[Route('/create', name: 'admin_v1_order_create')]
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
