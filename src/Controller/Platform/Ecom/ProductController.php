<?php

namespace App\Controller\Platform\Ecom;

use App\Controller\Platform\PlatformController;
use App\Form\Platform\Ecom\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends PlatformController
{
    #[Route('/{_locale}/ecom/v1/products/', name: 'ecom_v1_products')]
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $products = $this->doctrine->getRepository('App\Entity\Platform\Ecom\Product')->findBy(['instance' => $this->currentInstance]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Termékek',
            'tableHead' => [
                'name' => 'Név',
                'shortDescription' => 'Rövid leírás',
                'sku' => 'SKU',
                'price' => 'Ár',
                'salePrice' => 'Akciós ár',
                'saleStartDate' => 'Akció kezdete',
                'saleEndDate' => 'Akció vége',
                'currency' => 'Pénznem',
                'status' => 'Státusz',
            ],
            'tableBody' => $products,
            'actions' => [
                'new', 'edit', 'delete'
            ],
        ]);
    }

    #[Route('/{_locale}/ecom/v1/products/new', name: 'ecom_v1_products_new')]
    public function new(Request $request): Response
    {
        // handle form submission
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('ecom_v1_products');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új termék',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }
}

