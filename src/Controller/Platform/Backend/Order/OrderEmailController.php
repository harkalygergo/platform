<?php

namespace App\Controller\Platform\Backend\Order;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\Order\OrderEmailTemplate;
use App\Entity\Platform\User;
use App\Form\Platform\Order\OrderEmailTemplateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/order-email')]
#[IsGranted(User::ROLE_USER)]
class OrderEmailController extends PlatformBackendController
{
    #[Route('/', name: 'admin_v1_order_email_index', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $templates = $this->doctrine->getRepository(OrderEmailTemplate::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Order email templates',
            'tableHead' => [
                'orderStatus' => 'Order status',
                'subject' => 'Subject',
                'isActive' => 'Active',
            ],
            'tableBody' => $templates,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_order_email_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $template = new OrderEmailTemplate();
        $form = $this->createForm(OrderEmailTemplateType::class, $template);

        return $this->platformBackendNew($request, $form, 'admin_v1_order_email_index');
    }

    #[Route('/edit/{id}', name: 'admin_v1_order_email_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrderEmailTemplate $orderEmailTemplate): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($orderEmailTemplate->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute('admin_v1_order_email_index');
        }

        $form = $this->createForm(OrderEmailTemplateType::class, $orderEmailTemplate);

        return $this->platformBackendEdit($request, $form, $orderEmailTemplate, 'admin_v1_order_email_index');
    }

    #[Route('/delete/{id}', name: 'admin_v1_order_email_delete', methods: ['GET'])]
    public function delete(OrderEmailTemplate $orderEmailTemplate): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($orderEmailTemplate->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute('admin_v1_order_email_index');
        }

        return $this->platformBackendDelete($orderEmailTemplate, 'admin_v1_order_email_index');
    }
}
