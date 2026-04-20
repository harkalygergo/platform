<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\BillingProfile;
use App\Entity\Platform\Order;
use App\Entity\Platform\Order\OrderEmailTemplate;
use App\Entity\Platform\Service;
use App\Entity\Platform\User;
use App\Enum\Platform\OrderStatusEnum;
use App\Form\Platform\Shop\Webshop\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/order')]
class OrderController extends PlatformBackendController
{
    private const string redirectToRoute = 'ecom_order_index';

    #[Route('/', name: 'ecom_order_index')]
    public function index(Request $request): Response
    {
        $orders = $this->doctrine->getRepository(Order::class)->findBy([
            'instance' => $this->currentInstance,
        ], ['createdAt' => 'DESC']);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelések',
            'tableHead' => [
                'id' => 'Azonosító',
                'createdAt' => 'Dátum',
                'status' => 'Státusz',
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
                //'currency' => 'Pénznem',
                //'billingVatNumber' => 'Adószám',
                //'billingZip' => 'Számlázás irányítószám',
                //'billingCity' => 'Számlázás település',
                //'billingAddress' => 'Számlázás cím',
                'items' => 'Tételek',
                //'paymentToken' => 'paymentToken',
            ],
            'tableBody' => $orders,
            'actions' => [
                'new',
                'view',
                'edit',
                'delete'
            ],
        ]);
    }

    #[Route('/view/{id}', name: 'ecom_order_index_view')]
    public function view(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        $content = $order->getFirstName().' '.$order->getLastName()
            .'<br>'.$order->getPhone()
            .'<br>'.$order->getEmail()
            .'<br>'.$order->getTotal().' '.$order->getCurrency()
            .'<br>'.$order->getComment()
            .'<hr><h2>Számlázás</h2>'
            .'<br>'.$order->getBillingProfile()
            .'<br>'.$order->getBillingCountry()
            .'<br>'.$order->getBillingZip()
            .'<br>'.$order->getBillingCity()
            .'<br>'.$order->getBillingAddress()
            .'<br>'.$order->getBillingVatNumber()
            .'<hr><h2>Szállítás</h2>'
            .'<br>'.$order->getShippingAddress()
            .'<br>'.$order->getShippingMethod()
        ;

        if (!is_null($order->getItems())) {
            $content .= '<hr><h2>Tételek</h2><ul>';
            foreach($order->getItems() as $item) {
                $content .= '<li>'.$item.'</li>';
            }
            $content .= '</ul>';
        }

        return $this->render('platform/backend/v1/content.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $order->getName(). ' rendelés megtekintése',
            'content' => $content
        ]);
    }

    #[Route('/new/', name: 'ecom_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'ecom_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $originalStatus = $entity->getStatus();

        $form = $this->createForm(OrderType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', $this->translator->trans('action.edited') . ': ' . $entity->getName());

            if ($entity->getStatus() !== $originalStatus) {
                $this->sendOrderStatusEmail($entity, $entity->getStatus());
            }

            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('action.edit') . ': ' . $entity->getName(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    private function sendOrderStatusEmail(Order $order, OrderStatusEnum $newStatus): void
    {
        if ($order->getEmail() === null) {
            return;
        }

        $template = $this->doctrine->getRepository(OrderEmailTemplate::class)->findOneBy([
            'instance' => $this->currentInstance,
            'orderStatus' => $newStatus,
            'isActive' => true,
        ]);

        if ($template === null) {
            return;
        }

        $this->sendMail(
            [$order->getEmail()],
            $template->getSubject(),
            $template->getPlainTextContent() ?? '',
            null,
            $template->getHtmlContent() ?? ''
        );
    }

    /*
    #[Route('/edit/{id}/', name: 'ecom_order_edit')]
    public function edit(Request $request, int $id): Response
    {
        $order = $this->doctrine->getRepository(Order::class)->find($id);

        return $this->render('platform/backend/v1/edit.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendelés szerkesztése',
            'order' => $order,
        ]);
    }
    */

    #[Route('/delete/{id}', name: 'ecom_order_delete')]
    public function delete(Request $request, Order $id): Response
    {
        // check if order instance matches current instance
        if ($id->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('You do not have permission'));
            return $this->redirectToRoute(self::redirectToRoute);
            //throw $this->createAccessDeniedException($this->translator->trans('You do not have permission'));
        }

        // check if order exists
        if (!$id) {
            $this->addFlash('error', 'Entitás nem található.');
            return $this->redirectToRoute(self::redirectToRoute);
        }

        // remove order
        $this->doctrine->getManager()->remove($id);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute(self::redirectToRoute);
    }

    #[Route('/multiple/{action}/{ids}', name: 'ecom_order_multiple')]
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

        return $this->redirectToRoute('ecom_order_index');
    }

    #[Route('/create', name: 'ecom_order_create')]
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
