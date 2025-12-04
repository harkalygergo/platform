<?php

namespace  App\Controller\Platform\Backend\Webshop;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Webshop\ShippingMethod;
use App\Form\Platform\Webshop\ShippingMethodType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/webshop/shipping-methods')]
class ShippingMethodController extends PlatformController
{
    #[Route('/', name: 'admin_v1_webshop_shippingmethod_index')]
    public function index(Request $request): Response
    {
        $paymentMethods = $this->doctrine->getRepository(ShippingMethod::class)->findBy([
            'instance' => $this->currentInstance,
        ]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Szállítási módok',
            'tableHead' => [
                'name' => 'Név',
                'description' => 'Leírás',
                'type' => 'Típus',
                'status' => 'Státusz',
            ],
            'tableBody' => $paymentMethods,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_webshop_shippingmethod_add')]
    public function add(Request $request): Response
    {
        $paymentMethod = new ShippingMethod();
        $form = $this->createForm(ShippingMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethod->setInstance($this->currentInstance);
            $paymentMethod->setCreatedBy($this->getUser());
            $this->doctrine->getManager()->persist($paymentMethod);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_webshop_shippingmethod_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Szállítási mód hozzáadása',
            'form' => $form->createView(),
        ]);
    }

    // add edit and delete methods as needed
    #[Route('/edit/{id}/', name: 'admin_v1_webshop_shippingmethod_edit')]
    public function edit(Request $request, int $id): Response
    {
        $paymentMethod = $this->doctrine->getRepository(ShippingMethod::class)->find($id);
        if (!$paymentMethod) {
            throw $this->createNotFoundException('Szállítási mód nem található.');
        }

        $form = $this->createForm(ShippingMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethod->setUpdatedBy($this->getUser());
            $paymentMethod->setUpdatedAt(new \DateTime());
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_webshop_shippingmethod_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Szállítási mód szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_v1_webshop_shippingmethod_delete')]
    public function delete(Request $request, int $id): Response
    {
        $paymentMethod = $this->doctrine->getRepository(ShippingMethod::class)->find($id);
        if (!$paymentMethod) {
            $this->addFlash('error', 'Not found');
            return $this->redirectToRoute('admin_v1_webshop_shippingmethod_index');
        }

        // check if payment method instance matches current instance
        if ($paymentMethod->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('You do not have permission'));
            return $this->redirectToRoute('admin_v1_webshop_shippingmethod_index');
        }

        $em = $this->doctrine->getManager();
        $em->remove($paymentMethod);
        $em->flush();
        $this->addFlash('success', 'Deleted successfully');
        return $this->redirectToRoute('admin_v1_webshop_shippingmethod_index');
    }
}
