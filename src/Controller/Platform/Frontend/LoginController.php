<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends PlatformController
{
    //#[Route('/', name: 'honeypot')]
    #[Route('/admin', name: 'honeypot_admin')]
    #[Route('/wp-admin', name: 'honeypot_wp_admin')]
    #[Route('/administrator', name: 'honeypot_administrator')]
    #[Route('/login', name: 'honeypot_login')]
    #[Route('/register', name: 'honeypot_register')]
    #[Route('/{_locale}/admin', name: 'locale_admin')]
    public function honeypot(): Response
    {
        // if a user is logged in, redirect to the dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_post');
        }

        $environment = $this->getPlatformBasicEnviroments();

        return $this->render('platform/frontend/restricted.html.twig', $environment);
    }

    #[Route('/{_locale}/admin/v1', name: 'login')]
    public function index(Request $request, Security $security): Response
    {
        // if it is a posted request, redirect to the dashboard
        if ($request->isMethod('POST')) {
            $postedData = $request->request->all();
            $username = $postedData['username'];
            // check in users table if the username exists as email
            $userRepo = $this->doctrine->getRepository(User::class);
            $user = $userRepo->findOneBy(['email' => $username]);
            if ($user) {
                // check if the password is correct
                $password = $postedData['password'];
                if (password_verify($password, $user->getPassword())) {
                    // if the password is correct, redirect to the dashboard
                    $security->login($user, 'security.authenticator.form_login.main', 'main');

                    $user->setLastLogin(new \DateTimeImmutable());
                    $this->doctrine->getManager()->flush();

                    // set user's defaultInstance to cookie
                    $defaultInstance = $user->getDefaultInstance();
                    if ($defaultInstance) {
                        setcookie('currentInstance', $defaultInstance->getId(), time() + 60 * 60 * 24 * 30, '/');
                    }

                    return $this->redirectToRoute('admin_post');
                }
            }
        }

        if ($this->getUser()) {
            return $this->redirectToRoute('admin_post');
        }

        // if it is a post request, redirect to the dashboard
        if ($this->isCsrfTokenValid('authenticate', $request->get('_csrf_token'))) {
            return $this->redirectToRoute('admin_post');
        }

        $queryParams = $request->query->all();

        return $this->render('platform/frontend/login.html.twig', [
            'title' => $queryParams['project'] ?? 'Platform',
        ]);
    }

    #[Route('/{_locale}/admin/logout', name: 'admin_logout')]
    public function logout(Security $security): Response
    {
        if ($this->getUser()) {
            $security->logout();
        }

        return $this->redirectToRoute('login');
    }

    #[Route('/{_locale}/reset-password', name: 'reset_password')]
    public function resetPassword(Request $request, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        if ($request->isMethod('post') && $this->isCsrfTokenValid('reset-password', $_POST['_csrf_token'])) {
            // check if the email exists in the users table
            $email = $_POST['username'];
            $userRepo = $this->doctrine->getRepository(User::class);
            $user = $userRepo->findOneBy(['email' => $email]);
            if ($user) {

                // generate and set a new password for user
                $newPassword = bin2hex(random_bytes(8));
                $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                $this->doctrine->getManager()->flush();

                $toAddresses = [$user->getEmail()];

                $this->sendMail(
                    $toAddresses,
                    $this->translator->trans('account.reset password'),
                    $email."\n\n".$newPassword
                );
            }

            return $this->redirectToRoute('security_login');
        }

        return $this->render('platform/frontend/reset-password.html.twig');
    }
}
