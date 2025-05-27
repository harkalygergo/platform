<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\WebsitePost;
use App\Form\Platform\Website\WebsitePostType;
use App\Repository\Platform\Website\WebsitePostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website/posts')]
class WebsitePostController extends PlatformController
{
    #[Route('/{id}/', name: 'admin_v1_website_posts')]
    public function index(\App\Entity\Platform\Website\Website $id, WebsitePostRepository $websitePostRepository): Response
    {
        $pagesByWebsite = $websitePostRepository->findByWebsiteId($id->getId());

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => $id->getDomain() . ' bejegyzések',
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

    #[Route('/{id}/new/', name: 'admin_v1_website_post_new')]
    public function new(Request $request, \App\Entity\Platform\Website\Website $id): Response
    {
        $entity = new WebsitePost();
        $form = $this->createForm(WebsitePostType::class, $entity, [
            'website' => $id,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websitePost = $form->getData();
            $websitePost->setWebsite($id);
            $this->doctrine->getManager()->persist($websitePost);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_posts', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új bejegyzés',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // create edit function
    #[Route('/{id}/edit/{page}', name: 'admin_v1_website_post_edit')]
    public function edit(Request $request, \App\Entity\Platform\Website\Website $id, WebsitePost $page): Response
    {
        $form = $this->createForm(WebsitePostType::class, $page, [
            'website' => $id,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_posts', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Bejegyzés szerkesztése',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete/{page}', name: 'admin_v1_website_post_delete')]
    public function delete(Request $request, \App\Entity\Platform\Website\Website $id, WebsitePost $page): Response
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

        return $this->redirectToRoute('admin_v1_website_posts', [
            'id' => $id->getId(),
        ]);
    }
}

