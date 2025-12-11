<?php
namespace App\Form\Platform;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('current_password', PasswordType::class, [
                'label' => 'Jelenlegi jelszó',
                'mapped' => false, // Not mapped to the entity
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your current password.',
                    ]),
                ],
            ])
            ->add('new_password', PasswordType::class, [
                'label' => 'Új jelszó',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a new password.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Minimum {{ limit }} karakter.',
                    ]),
                    /*
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Your password must contain at least one letter and one number.',
                    ]),
                    */
                ],
            ])
            ->add('new_password_confirm', PasswordType::class, [
                'label' => 'Új jelszó megerősítése',
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please confirm your new password.',
                    ]),
                ],
            ]);

        // Add custom validation
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            $newPassword = $form->get('new_password')->getData();
            $newPasswordConfirm = $form->get('new_password_confirm')->getData();

            if ($newPassword !== $newPasswordConfirm) {
                $form->get('new_password_confirm')->addError(new \Symfony\Component\Form\FormError('The password confirmation does not match the new password.'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
