<?php

namespace App\Controller\Platform;

use App\Entity\Platform\User;
use App\Repository\Platform\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'security_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        UserRepository $userRepository,
    ): Response {
        $errors = [];
        $data = [
            'fullName' => '',
            'username' => '',
            'email' => '',
        ];

        if ($request->isMethod('POST')) {
            $token = $request->request->get('_csrf_token');
            if (!$this->isCsrfTokenValid('register', $token)) {
                $errors[] = 'Invalid CSRF token.';
            } else {
                $data['fullName'] = trim((string) $request->request->get('fullName', ''));
                $data['username'] = trim((string) $request->request->get('username', ''));
                $data['email'] = trim((string) $request->request->get('email', ''));
                $plainPassword = (string) $request->request->get('password', '');

                if ($data['fullName'] === '') {
                    $errors[] = 'Full name is required.';
                }

                if ($data['username'] === '' || strlen($data['username']) < 2 || strlen($data['username']) > 50) {
                    $errors[] = 'Username is required (2-50 characters).';
                }

                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'A valid email is required.';
                }

                if ($plainPassword === '' || strlen($plainPassword) < 6) {
                    $errors[] = 'Password is required and must be at least 6 characters.';
                }

                // uniqueness checks
                if ($userRepository->findOneBy(['username' => $data['username']])) {
                    $errors[] = 'This username is already taken.';
                }
                if ($userRepository->findOneBy(['email' => $data['email']])) {
                    $errors[] = 'This email is already used.';
                }

                if (empty($errors)) {
                    $user = new User();
                    $user->setFullName($data['fullName']);
                    $user->setUsername($data['username']);
                    $user->setEmail($data['email']);

                    $hashed = $passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashed);

                    // ensure at least ROLE_USER
                    $user->setRoles([]);

                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Registration successful. You can now log in.');

                    return $this->redirectToRoute('security_login');
                }
            }
        }

        return $this->render('platform/security/register.html.twig', [
            'errors' => $errors,
            'data' => $data,
        ]);
    }
}

