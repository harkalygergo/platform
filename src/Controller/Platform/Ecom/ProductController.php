<?php

namespace App\Controller\Platform\Ecom;

use App\Controller\Platform\PlatformController;
use App\Form\Platform\Ecom\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends PlatformController
{
    #[Route('/{_locale}/ecom/v1/products/', name: 'admin_v1_shop_products')]
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
                'websites' => 'Websites',
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
            'extraActions' => [
                'deploy' => [
                    'route' => 'admin_v1_ecom_product_deploy',
                    'label' => '&lt/&gt; Deploy',
                ]
            ]
        ]);
    }

    #[Route('/{_locale}/ecom/v1/products/new', name: 'admin_v1_shop_products_new')]
    public function new(Request $request): Response
    {
        // handle form submission
        $form = $this->createForm(ProductType::class, null, [
            'currentInstance' => $this->currentInstance,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_shop_products');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új termék',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/ecom/v1/products/edit/{id}', name: 'admin_v1_shop_products_edit')]
    public function edit(Request $request, $id): Response
    {
        $product = $this->doctrine->getRepository('App\Entity\Platform\Ecom\Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Termék nem található');
        }

        $form = $this->createForm(ProductType::class, $product, [
            'currentInstance' => $this->currentInstance,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_shop_products');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Szerkesztés: ' . $product->getName(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }
}

