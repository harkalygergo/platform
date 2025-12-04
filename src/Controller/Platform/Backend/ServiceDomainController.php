<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Repository\Platform\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_ADMIN)]
class ServiceDomainController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/domains', name: 'admin_v1_domains')]
    public function index(Request $request): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        // get user's first instance
        $instance = $this->currentInstance;

        // get instances services where type is domain
        $services = (new ServiceRepository($this->doctrine))->findBy(['instance' => $instance, 'type' => 'domain']);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Domainek',
            'tableHead' => [
                'name' => 'Név',
                'description' => 'Leírás',
                'fee' => 'Díj',
                'currency' => 'Pénznem',
                'type' => 'Típus',
                'status' => 'Státusz',
            ],
            'tableBody' => $services,
            'actions' => [
            ],
        ]);
    }
}
