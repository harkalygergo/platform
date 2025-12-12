<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Form\Platform\UserPasswordType;
use App\Form\Platform\UserType;
use App\Repository\Platform\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
class UserController extends PlatformController
{
    /*
    public function __construct(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine, TranslatorInterface $translator, KernelInterface $kernel)
    {
        parent::__construct($requestStack, $doctrine, $translator, $kernel);
    }
    */

    #[Route('/{_locale}/admin/v1/account/edit', name: 'admin_v1_account_edit')]
    public function editAccount(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute('admin_v1_account_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Saját adataim szerkesztése',
            'form' => $form->createView(),
        ]);

    }

    #[Route('/{_locale}/admin/v1/account/password-change', name: 'admin_v1_account_password_change')]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Create the form
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the currently logged-in user
            $user = $this->getUser();
            if (!$user) {
                throw $this->createAccessDeniedException('You must be logged in to change your password.');
            }

            // Get the new password from the form
            $newPassword = $form->get('new_password')->getData();

            // Hash the new password and set it on the user entity
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            // Persist the changes
            $entityManager->flush();

            // Add a success message and redirect
            $this->addFlash('success', $this->translator->trans('event.saved successfully'));

            return $this->redirectToRoute('admin_v1_account_password_change');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => $this->translator->trans('account.change password'),
            'form' => $form->createView(),
        ]);
    }

    // this function needs to be available for users to let switch back to superadmin
    #[Route('/{_locale}/admin/v1/superadmin/switch-user/{id}', name: 'admin_v1_superadmin_switch_user')]
    function switchUser(Security $security, int $id): RedirectResponse
    {
        $user = (new UserRepository($this->doctrine))->find($id);

        // change currentInstance cookie to the user's default instance
        $instance = $user->getDefaultInstance();
        if ($instance) {
            setcookie('currentInstance', $instance->getId(), time() + 60 * 60 * 24 * 30, '/');
        } else {
            $this->addFlash('danger', 'Nincs alapértelmezett instance.');
            return $this->redirectToRoute('admin_v1_dashboard');
        }


        $security->login($user, 'security.authenticator.form_login.main', 'main');

        return $this->redirectToRoute('admin_v1_instances_switch', ['instance' => $user->getDefaultInstance()->getId()]);
    }

    // function to add new user by superadmin
    #[Route('/{_locale}/admin/v1/superadmin/users/new', name: 'admin_v1_superadmin_user_new')]
    public function superAdminUserNew(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getPassword()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getUsername());
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('action.created'));
            return $this->redirectToRoute('admin_v1_superadmin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('superadmin'),
            'title' => 'Új felhasználó létrehozása',
            'form' => $form->createView(),
        ]);
    }
}
