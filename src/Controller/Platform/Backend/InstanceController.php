<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class InstanceController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/instances/', name: 'admin_v1_instances')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $instances = $user->getInstances();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Instances',
            'tableHead' => [
                'name' => 'Name',
                'status' => 'Status',
            ],
            'tableBody' => $instances,
            'actions' => [
                'switch',
            ],
        ]);
    }

    #[Route('/{_locale}/admin/v1/instances/switch/{instance}', name: 'admin_v1_instances_switch')]
    public function switch(Instance $instance)
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($instance);

        $user = $this->getUser();

        // check if logged in user and instance has connection
        if (!$instance || !$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_instances');
        }

        $this->addFlash('success', $this->translator->trans('event.saved successfully'));
        setcookie('currentInstance', $instance->getId(), time() + 60 * 60 * 24 * 30, '/');

        return $this->redirectToRoute('admin_v1_dashboard');
    }


    #[Route('/{_locale}/admin/v1/instances/add', name: 'admin_v1_instances_add')]
    public function add(Request $request): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$instance->setUser($this->getUser());
            $this->doctrine->getManager()->persist($instance);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_instances');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Add instance',
            'form' => $form->createView(),
        ]);
    }
}
