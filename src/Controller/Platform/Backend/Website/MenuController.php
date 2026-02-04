<?php

namespace  App\Controller\Platform\Backend\Website;


use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Menu;
use App\Form\Platform\Website\MenuType;
use App\Repository\Platform\Website\MenuRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website/menus')]
class MenuController extends PlatformController
{
    #[Route('/', name: 'admin_v1_website_menus')]
    public function index(/*\App\Entity\Platform\Website\Website $id, */MenuRepository $menuRepository): Response
    {
        // get current instance first website
        $id = $this->currentInstance->getWebsites()->first();

        $menusByWebsite = $menuRepository->findByWebsiteId($id->getId());

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => $id->getDomain() . ' menük',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'title' => 'Cím',
                'slug' => 'Slug',
                'position' => 'Pozíció',
                'parent' => 'Szülő menü',
                'status' => 'Státusz',
            ],
            'tableBody' => $menusByWebsite,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_website_menu_new')]
    public function new(Request $request): Response
    {
        $id = $this->currentInstance->getWebsites()->first();

        $form = $this->createForm(MenuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu = $form->getData();
            $menu->setWebsite($id);
            $this->doctrine->getManager()->persist($menu);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_menus', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új menü',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // create edit and delete methods as needed
    #[Route('/edit/{menu}/', name: 'admin_v1_website_menu_edit')]
    public function edit(Menu $menu, Request $request, MenuRepository $menuRepository): Response
    {

        if (!$menu) {
            throw $this->createNotFoundException('Menu not found');
        }

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_menus', [
                //'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Szerkesztés: ' . $menu->getTitle(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete/{menu}', name: 'admin_v1_website_menu_delete')]
    public function delete(\App\Entity\Platform\Website\Website $id, Menu $menu, Request $request, MenuRepository $menuRepository): Response
    {
        if (!$menu) {
            throw $this->createNotFoundException('Menu not found');
        }

        $em = $this->doctrine->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->redirectToRoute('admin_v1_website_menus', [
            'id' => $id->getId(),
        ]);
    }

    #[Route('/{id}/multiple/{action}/{ids}', name: 'admin_v1_website_menu_multiple')]
    public function multiple(Request $request, \App\Entity\Platform\Website\Website $id, string $action, string $ids)
    {
        $idsArray = explode(',', $ids);

        switch ($action) {
            case 'delete':
                foreach ($idsArray as $id) {
                    $menu = $this->doctrine->getRepository(Menu::class)->find($id);
                    if ($menu) {
                        $this->doctrine->getManager()->remove($menu);
                    }
                }
                $this->doctrine->getManager()->flush();
                break;
            default:
                throw new \Exception('Unknown action: ' . $action);
        }

        return $this->redirectToRoute('admin_v1_website_menus', [
            'id' => $request->get('id'),
        ]);
    }
}
