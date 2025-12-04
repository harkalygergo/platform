<?php

namespace App\Controller\Platform\Backend\API;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\API\API;
use App\Entity\Platform\User;
use App\Form\Platform\API\APIType;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/api')]
#[IsGranted(User::ROLE_USER)]
class APIController extends PlatformController
{
    #[Route('/', name: 'admin_v1_api_index')]
    public function index()
    {
        // get all APIs where instance is current instance
        $apis = $this->doctrine->getRepository(API::class)->findBy(['instance' => $this->currentInstance]);
        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'API',
            'tableHead' => [
                'name' => 'Név',
                'description' => 'Leírás',
                'domain' => 'Domain',
                'publicKey' => 'Nyilvános kulcs',
                'secret' => 'Titkos kulcs',
                'status' => 'Státusz',
            ],
            'tableBody' => $apis,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new', name: 'admin_v1_api_add')]
    public function new()
    {
        $api = new API();
        $form = $this->createForm(APIType::class, $api);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $api->setInstance($this->currentInstance);
            $api->setCreatedBy($this->getUser());
            $this->doctrine->getManager()->persist($api);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_api_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új API létrehozása',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_v1_api_edit')]
    public function edit(API $api)
    {
        $form = $this->createForm(APIType::class, $api);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $api->setUpdatedBy($this->getUser());
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_api_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'API szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_v1_api_delete')]
    public function delete(API $api)
    {
        $this->doctrine->getManager()->remove($api);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_api_index');
    }
}
