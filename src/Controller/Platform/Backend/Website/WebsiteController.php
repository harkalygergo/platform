<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Form\Platform\Website\WebsiteType;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website')]
class WebsiteController extends PlatformController
{
    #[Route('/', name: 'admin_v1_website_index', methods: ['GET'])]
    public function index(WebsiteRepository $websiteRepository): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Honlapok',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'domain' => 'Domain',
                'name' => $this->translator->trans('global.name'),
                'title' => $this->translator->trans('global.title'),
            ],
            'tableBody' => $websiteRepository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'edit',
            ],
        ]);
    }

    // new function
    #[Route('/new', name: 'admin_v1_website_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        // handle form submission
        $form = $this->createForm(WebsiteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $website = $form->getData();
            $website->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($website);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új honlap',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }
}
