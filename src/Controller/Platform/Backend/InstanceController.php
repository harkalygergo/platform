<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Instance\InstanceFeed;
use App\Entity\Platform\User;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/instances')]
class InstanceController extends PlatformController
{
    #[Route('/', name: 'admin_v1_instances')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $instances = $user->getInstances();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Instances',
            'tableHead' => [
                'name' => 'Name',
                'type' => 'Type',
                'status' => 'Status',
            ],
            'tableBody' => $instances,
            'actions' => [
                'switchInstance',
            ],
        ]);
    }

    #[Route('/switch/{instance}', name: 'admin_v1_instances_switch')]
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


    #[Route('/add', name: 'admin_v1_instances_add')]
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

    // show instance users
    #[Route('/users', name: 'admin_v1_instances_users')]
    public function showUsers(): Response
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($this->currentInstance);

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_dashboard');
        }

        $users = $instance->getUsers();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Vállalkozás és szervezet felhasználói',
            'tableHead' => [
                'namePrefix' => 'Előtag',
                'lastName' => $this->translator->trans('user.lastName'),
                'firstName' => $this->translator->trans('user.firstName'),
                'nickName' => 'Becenév',
                'position' => 'Beosztás',
                'birthDate' => 'Születési dátum',
                'phone' => $this->translator->trans('user.phone'),
                'email' => 'E-mail',
                'status' => $this->translator->trans('global.status'),
                'roles' => 'Szerepkörök',
                'lastLogin' => 'Utolsó belépés',
                'lastActivation' => 'Utolsó aktivitás',
            ],
            'tableBody' => $users,
            'actions' => [
            ],
        ]);
    }

    // create a function to edit instance Intranet content with WYSIWYG editor
    #[Route('/edit-intranet', name: 'admin_v1_instances_edit_intranet')]
    public function editIntranet(Request $request): Response
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($this->currentInstance);

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_dashboard');
        }

        // use formBuilder to create a form for editing the intranet content
        $form = $this->createFormBuilder($instance)
            ->add('intranet', TextareaType::class, [
                'label' => 'Intranet tartalom',
                'attr' => [
                    'class' => 'form-control summernote',
                    'rows' => 10,
                ],
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->persist($instance);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'Az intranet tartalom sikeresen frissítve lett.');

            // add info to instance feed
            $instanceFeed = new InstanceFeed();
            $instanceFeed->setInstance($instance);
            $instanceFeed->setMessage('Intranet updated.');
            $instanceFeed->setCreatedBy($this->getUser());
            $this->doctrine->getManager()->persist($instanceFeed);
            $this->doctrine->getManager()->flush();

            //return $this->redirectToRoute('admin_v1_instances');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Intranet tartalom szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    // show current instance intranet content with content.html.twig template
    #[Route('/intranet', name: 'admin_v1_instances_intranet')]
    public function intranet(): Response
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($this->currentInstance);

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_dashboard');
        }

        return $this->render('platform/backend/v1/content.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Intranet',
            'content' => $instance->getIntranet(),
        ]);
    }

    private function getFeedController()
    {
        return $this->container->get('App\Controller\Platform\Backend\Instance\InstanceFeedController');
    }
}
