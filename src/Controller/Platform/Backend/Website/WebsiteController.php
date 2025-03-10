<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\Backend\BackendController;
use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Repository\Platform\Website\WebsiteRepository;
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
                'domain' => $this->translator->trans('user.namePrefix'),
                'name' => $this->translator->trans('user.lastName'),
            ],
            'tableBody' => $websiteRepository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'edit',
            ],
        ]);
    }
}
