<?php

namespace App\Controller\Platform\Backend\Superadmin;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_SUPERADMIN)]
#[Route('/{_locale}/admin/v1/superadmin/templates')]
class SuperAdminTemplateController extends PlatformBackendController
{
    #[Route('/', name: 'admin_v1_superadmin_templates_index')]
    public function superAdminTemplateIndex(): Response
    {
        $tableBody = $this->doctrine->getRepository('App\Entity\Platform\Template')->findAll();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Sablonok',
            'tableHead' => [
                'name' => 'Megnevezés',
                'code' => 'Kód',
                'description' => 'leírás',
                'position' => 'Pozíció',
                'imagePath' => 'imagePath',
                'isActive' => 'Státusz'
            ],
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }
}
