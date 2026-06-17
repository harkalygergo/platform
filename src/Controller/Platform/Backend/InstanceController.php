<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Instance\InstanceFeed;
use App\Entity\Platform\User;
use App\Form\Platform\InstanceType;
use App\Repository\Platform\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/instances')]
class InstanceController extends PlatformController
{
    #[Route('/', name: 'admin_v1_sys_instances')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $instances = $user->getInstances();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Instances',
            'tableHead' => [
                'name' => 'Name',
                'code' => 'Code',
                'email' => 'Email',
                'owner' => 'Owner',
                'type' => 'Type',
                'status' => 'Status',
            ],
            'tableBody' => $instances,
            'actions' => [
                'edit',
                'switchInstance',
            ],
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_v1_home_instances_edit')]
    public function edit(Request $request, Instance $id): Response
    {
        $instance = $id;

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_sys_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_home_homepage');
        }

        // use formBuilder to create a form for editing the intranet content
        $form = $this->createFormBuilder($instance)
            // add instance "name" field as text field
            ->add('name', TextType::class, [
                'label' => 'Vállalkozás és szervezet neve',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                ],
                'required' => true,
            ])
            ->add('code', TextType::class, [
                'required' => true,
                'attr' => [
                    'readonly' => 'true',
                    'maxlength' => 32,
                    'class' => 'form-control',
                ],
            ])
            ->add('plan', ChoiceType::class, [
                'label' => 'Díjcsomag',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'FREE - 0,- forint / hó' => 'free',
                    'START - 9.990,- forint / hó' => 'start',
                    'PRO - 19.990,- forint / hó' => 'pro',
                    'PREMIUM - 39.990,- forint / hó' => 'premium',
                    'VIP - 79.990,- forint / hó' => 'vip',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                    'placeholder' => '',
                    'type' => 'email',
                    'autocomplete' => 'email',
                    'autofocus' => true,
                    'spellcheck' => 'false',
                    'autocapitalize' => 'off',
                    'autocorrect' => 'off',
                ]
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) use ($instance) {
                    return $userRepository->findByInstance($instance);
                },
                'choice_label' => function (User $user) {
                    return $user->getFullName(). ' '. $user->getPosition(). ' (' . $user->getPhone() . '; ' . $user->getEmail() . ')';
                },
                'label' => 'Tulajdonos',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('deletePassedEvents', CheckboxType::class, [
                'required' => false,
                'attr' => [
                ],
            ])
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
            $instance->setUpdatedAt(new \DateTimeImmutable('now'));
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

            //return $this->redirectToRoute('admin_v1_sys_instances');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Intranet tartalom szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/switch/{instance}', name: 'admin_v1_home_instances_switch')]
    public function switch(Instance $instance)
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($instance);

        $user = $this->getUser();

        // check if logged in user and instance has connection
        if (!$instance || !$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_sys_instances');
        }

        setcookie('currentInstance', $instance->getId(), time() + 60 * 60 * 24 * 30, '/');
        $this->addFlash('success', $this->currentInstance->getName(). ' -> ' . $instance->getName(). ' '. $this->translator->trans('action.saved'));

        return $this->redirectToRoute('admin_v1_home_homepage');
    }


    #[Route('/add', name: 'admin_v1_home_instances_add')]
    public function add(Request $request): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$instance->setUser($this->getUser());
            $this->doctrine->getManager()->persist($instance);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_sys_instances');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Add instance',
            'form' => $form->createView(),
        ]);
    }

    // show instance users
    #[Route('/users', name: 'admin_v1_home_instances_users')]
    public function showUsers(): Response
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($this->currentInstance);

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_sys_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_home_homepage');
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

    // show current instance intranet content with content.html.twig template
    #[Route('/intranet', name: 'admin_v1_home_instances_intranet')]
    public function intranet(): Response
    {
        $instance = $this->doctrine->getRepository(Instance::class)->find($this->currentInstance);

        if (!$instance) {
            $this->addFlash('danger', 'Az oldal nem található.');

            return $this->redirectToRoute('admin_v1_sys_instances');
        }

        // check if logged in user and instance has connection
        $user = $this->getUser();
        if (!$user->getInstances()->contains($instance)) {
            $this->addFlash('danger', 'Önnek nincs jogosultsága.');

            return $this->redirectToRoute('admin_v1_home_homepage');
        }

        return $this->render('platform/backend/v1/content.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $this->currentInstance->getName(). ' intranet',
            'content' => $instance->getIntranet(),
        ]);
    }

    private function getFeedController()
    {
        return $this->container->get('App\Controller\Platform\Backend\Instance\InstanceFeedController');
    }
}
