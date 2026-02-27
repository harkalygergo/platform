<?php

namespace App\Controller\Platform\Ecom;

use App\Controller\Platform\PlatformController;
use App\Form\Platform\Ecom\ProductCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryController extends PlatformController
{
    #[Route('/{_locale}/ecom/v1/product-categories/', name: 'ecom_v1_product_categories')]
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $categories = $this->doctrine->getRepository('App\Entity\Platform\Ecom\ProductCategory')->findBy(['instance' => $this->currentInstance]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Termék kategóriák',
            'tableHead' => [
                'name' => 'Név',
                'description' => 'Leírás',
                'parentCategory' => 'Szülő kategória',
            ],
            'tableBody' => $categories,
            'actions' => [
                'new', 'edit', 'delete'
            ],
        ]);
    }

    #[Route('/{_locale}/ecom/v1/product-categories/new', name: 'ecom_v1_product_categories_new')]
    public function new(Request $request): Response
    {
        // handle form submission
        $form = $this->createForm(ProductCategoryType::class, null, [
            'currentInstance' => $this->currentInstance,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('ecom_v1_product_categories');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új termék kategória',
            'form' => $form->createView(),
        ]);
    }
}
