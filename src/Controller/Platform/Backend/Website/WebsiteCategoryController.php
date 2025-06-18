<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\WebsiteCategory;
use App\Form\Platform\Website\WebsiteCategoryType;
use App\Repository\Platform\Website\WebsiteCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website/categories')]
class WebsiteCategoryController extends PlatformController
{
    #[Route('/{id}/', name: 'admin_v1_website_categories')]
    public function index(\App\Entity\Platform\Website\Website $id, WebsiteCategoryRepository $websiteCategoryRepository): Response
    {
        $pagesByWebsite = $websiteCategoryRepository->findByWebsiteId($id->getId());

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => $id->getDomain() . ' kategóriák',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'title' => 'Cím',
                'slug' => 'Slug',
                'status' => 'Státusz',
            ],
            'tableBody' => $pagesByWebsite,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/{id}/new/', name: 'admin_v1_website_category_new')]
    public function new(Request $request, \App\Entity\Platform\Website\Website $id): Response
    {
        $form = $this->createForm(WebsiteCategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websiteCategory = $form->getData();
            $websiteCategory->setWebsite($id);
            $this->doctrine->getManager()->persist($websiteCategory);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_categories', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új kategória',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // create edit function
    #[Route('/{id}/edit/{page}', name: 'admin_v1_website_category_edit')]
    public function edit(Request $request, \App\Entity\Platform\Website\Website $id, WebsiteCategory $page): Response
    {
        $form = $this->createForm(WebsiteCategoryType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_categories', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Bejegyzés szerkesztése',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete/{page}', name: 'admin_v1_website_category_delete')]
    public function delete(Request $request, \App\Entity\Platform\Website\Website $id, WebsiteCategory $page): Response
    {
        // check if page's website is the same as the current website
        if ($page->getWebsite() !== $id) {
            throw $this->createAccessDeniedException('You do not have permission to delete this page.');
        }

        // check if website's instance is the same as the current instance
        if ($page->getWebsite()->getInstance() !== $this->currentInstance) {
            throw $this->createAccessDeniedException('You do not have permission to delete this page.');
        }

        //if ($request->isMethod('POST')) {
        $this->doctrine->getManager()->remove($page);
        $this->doctrine->getManager()->flush();
        //}

        return $this->redirectToRoute('admin_v1_website_categories', [
            'id' => $id->getId(),
        ]);
    }

    #[Route('/{id}/multiple/{action}/{ids}', name: 'admin_v1_website_category_multiple')]
    public function multiple(Request $request, \App\Entity\Platform\Website\Website $id, string $action, string $ids): Response
    {
        $idsArray = explode(',', $ids);

        switch ($action) {
            case 'delete':
                foreach ($idsArray as $categoryId) {
                    $category = $this->doctrine->getRepository(WebsiteCategory::class)->find($categoryId);
                    if ($category && $category->getWebsite() === $id && $category->getWebsite()->getInstance() === $this->currentInstance) {
                        $this->doctrine->getManager()->remove($category);
                    }
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid action');
        }

        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_website_categories', [
            'id' => $id->getId(),
        ]);
    }
}

