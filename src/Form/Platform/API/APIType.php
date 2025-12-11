<?php

namespace App\Form\Platform\API;

use App\Entity\Platform\API\API;
use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use App\Repository\Platform\InstanceRepository;
use App\Repository\Platform\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class APIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'API Name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'API name is required']),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'API name must be at least {{ limit }} characters long',
                        'maxMessage' => 'API name cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('domain', TextType::class, [
                'label' => 'Domain',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Domain is required']),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:\d+)?(\/.*)?$/i',
                        'message' => 'Please enter a valid domain URL',
                    ]),
                ],
            ])
            ->add('publicKey', TextType::class, [
                'label' => 'API Key',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'API key is required']),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'API key must be at least {{ limit }} characters long',
                        'maxMessage' => 'API key cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('secret', TextType::class, [
                'label' => 'API Secret',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'API secret is required']),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'API secret must be at least {{ limit }} characters long',
                        'maxMessage' => 'API secret cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            /*
            ->add('permissions', ChoiceType::class, [
                // Assuming permissions are stored as an array of strings
                // Adjust the choices according to your needs
                'choices' => [
                    'Read' => 'read',
                    'Write' => 'write',
                    'Delete' => 'delete',
                ],
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Permissions',
                'constraints' => [
                    //new NotBlank(['message' => 'At least one permission is required']),
                ],
            ])
            */
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => API::class,
        ]);
    }
}
