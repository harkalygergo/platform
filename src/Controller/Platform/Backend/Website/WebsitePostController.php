<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Website;
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
    #[Route('/', name: 'admin_v1_website_posts')]
    public function index(WebsitePostRepository $websitePostRepository): Response
    {
        // get instance first website
        $id = $this->currentInstance->getWebsites()->first();

        $pagesByWebsite = $websitePostRepository->findByWebsiteId($id->getId());

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => $id->getName() . ' (' . $id->getDomain() . ') ' . $this->translator->trans('web.posts'),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'title' => 'Cím',
                'slug' => 'Slug',
                'metaTitle' => 'Meta cím',
                'status' => 'Státusz',
                'categories' => 'Kategória',
            ],
            'tableBody' => $pagesByWebsite,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_website_post_new')]
    public function new(Request $request): Response
    {
        $id = $this->currentInstance->getWebsites()->first();

        $entity = new WebsitePost();
        $form = $this->createForm(WebsitePostType::class, $entity, [
            'website' => $id,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websitePost = $form->getData();
            $websitePost->setWebsite($id);
            $websitePost->setInstance($this->currentInstance);
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
    #[Route('/edit/{post}', name: 'admin_v1_website_post_edit')]
    public function edit(Request $request, WebsitePost $post): Response
    {
        $id = $this->currentInstance->getWebsites()->first();

        $form = $this->createForm(WebsitePostType::class, $post, [
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
    public function delete(Request $request, Website $id, WebsitePost $page): Response
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

    #[Route('/{id}/multiple/{action}/{ids}', name: 'admin_v1_website_post_multiple')]
    public function multiple(Request $request, Website $id, string $action, string $ids)
    {
        $idsArray = explode(',', $ids);

        switch ($action) {
            case 'delete':
                foreach ($idsArray as $id) {
                    $page = $this->doctrine->getRepository(WebsitePost::class)->find($id);
                    if ($page) {
                        $this->doctrine->getManager()->remove($page);
                    }
                }
                $this->doctrine->getManager()->flush();
                break;
            default:
                throw new \Exception('Unknown action: ' . $action);
        }

        return $this->redirectToRoute('admin_v1_website_posts', [
            'id' => $request->get('id'),
        ]);
    }
}

