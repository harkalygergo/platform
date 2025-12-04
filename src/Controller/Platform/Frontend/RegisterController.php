<?php

namespace App\Controller\Platform\Frontend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use App\Form\Platform\UserType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RegisterController extends PlatformController
{
    #[Route('/{_locale}/{instanceSlug}/{instance}/register', name: 'register')]
    public function register(Request $request, MailerInterface $mailer, LoggerInterface $logger, string $instanceSlug, Instance $instance): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slugger = new AsciiSlugger();
            $generatedInstanceSlug = $slugger->slug($instance->getName())->lower()->toString();

            if ($generatedInstanceSlug === $instanceSlug) {
                $user->addInstance($instance);
                $user->setStatus(1);
                $newPassword = bin2hex(random_bytes(8));
                $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                $user->setRoles(['ROLE_USER']);

                $this->doctrine->getManager()->persist($user);
                $this->doctrine->getManager()->flush();

                $emailBody = "
                    Új felhasználói profil a(z) {$instance->getName()} fiókban! \n
                    Azonosító: {$user->getEmail()} \n
                    Jelszó: {$newPassword}
                ";

                $this->sendMail(
                    [$user->getEmail()],
                    $this->translator->trans('account.password created'),
                    $emailBody
                );

                return $this->redirectToRoute('login');
            }
        }

        return $this->render('platform/frontend/form.html.twig', [
            'title' => 'Regisztráció',
            'form' => $form->createView(),
        ]);
    }
}
