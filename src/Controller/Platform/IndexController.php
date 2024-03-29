<?php

namespace App\Controller\Platform;

use App\Entity\Platform\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
class IndexController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/{_locale}/admin/', name: 'admin_index')]
    public function adminIndex(): Response
    {
        return $this->render('platform/backend/v1/index.html.twig', [
        ]);
    }

    #[Route('/{_locale}/admin/account/edit', name: 'account_edit')]
    public function accountEdit(Request $request, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher): Response
    {
        // creates a task object and initializes some data for this example
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('language', ChoiceType::class, [
                'choices'  => [
                    'english' => 'en',
                    'magyar' => 'hu',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => $translator->trans('global.save'),
                'attr' => [
                    'class' => 'my-2 btn btn-lg btn-success'
                ]
            ])
            ->getForm();

        $data = [
            'title' => '<i class="bi bi-person"></i> Profil szerkesztése',
            'content' => '',
            'sidebar' => 'platform/backend/v1/sidebar_profile.html.twig',
            'form' => $form
        ];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();


            // configure different hashers via the factory
            $factory = new PasswordHasherFactory([
                'common' => ['algorithm' => 'bcrypt'],
                'sodium' => ['algorithm' => 'sodium'],
            ]);

            // retrieve the hasher using bcrypt
            $hasher = $factory->getPasswordHasher('common');
            $hash = $hasher->hash('plain');

            /*
            // verify that a given string matches the hash calculated above
            $hasher->verify($hash, 'invalid'); // false
            $hasher->verify($hash, 'plain'); // true

            $passwordHasherFactory = new PasswordHasherFactory([
                // auto hasher with default options for the User class (and children)
                User::class => ['algorithm' => 'auto'],

                // auto hasher with custom options for all PasswordAuthenticatedUserInterface instances
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 15,
                ],
            ]);


            $hashedPassword = $passwordHasherFactory->getPasswordHasher($user)->hash('alma');
            */
            $user->setPassword($hash);
            /*
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            */

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $data['notification'] = $user->getUsername(). ' felhasználó sikeresen létrehozva.';
        }

        return $this->render('platform/backend/v1/form.html.twig', $data);
    }
}
