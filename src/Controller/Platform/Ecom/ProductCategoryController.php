<?php

namespace App\Controller\Platform\Ecom;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\Ecom\ProductCategory;
use App\Entity\Platform\User;
use App\Form\Platform\Ecom\ProductCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/ecom/v1/product-categories')]
class ProductCategoryController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_shop_product_categories';

    #[Route('/', name: 'admin_v1_shop_product_categories')]
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
                'slug' => 'Slug',
                'description' => 'Leírás',
                'parentCategory' => 'Szülő kategória',
            ],
            'tableBody' => $categories,
            'actions' => [
                'new', 'edit', 'delete'
            ],
        ]);
    }

    /*
    #[Route('/{_locale}/ecom/v1/product-categories/new', name: 'admin_v1_shop_product_categories_new')]
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

            return $this->redirectToRoute('admin_v1_shop_product_categories');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új termék kategória',
            'form' => $form->createView(),
        ]);
    }
    */

    #[Route('/new/', name: 'admin_v1_shop_product_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ProductCategoryType::class, null, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_shop_product_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCategory $entity): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    #[Route('/delete/{id}', name: 'admin_v1_shop_product_categories_delete')]
    public function delete(Request $request, ProductCategory $id): Response
    {
        // check if order instance matches current instance
        if ($id->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('You do not have permission'));
            return $this->redirectToRoute(self::redirectToRoute);
            //throw $this->createAccessDeniedException($this->translator->trans('You do not have permission'));
        }

        if (!$id) {
            $this->addFlash('error', 'Entitás nem található.');
            return $this->redirectToRoute(self::redirectToRoute);
        }

        $this->doctrine->getManager()->remove($id);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute(self::redirectToRoute);
    }
}
