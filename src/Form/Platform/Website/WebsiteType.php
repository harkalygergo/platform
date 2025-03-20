<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domain', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('theme', TextType::class, [
                'label' => 'FTP password',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPHost', TextType::class, [
                'label' => 'FTP host',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPUser', TextType::class, [
                'label' => 'FTP user',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPPassword', PasswordType::class, [
                'label' => 'FTP password',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPPath', TextType::class, [
                'label' => 'FTP password',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // add meta title, description, keywords
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaDescription', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaKeywords', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // add meta author, robots
            ->add('metaAuthor', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaRobots', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'index, follow' => 'index, follow',
                    'noindex, nofollow' => 'noindex, nofollow',
                    'index, nofollow' => 'index, nofollow',
                    'noindex, follow' => 'noindex, follow',
                ],
            ])
            ->add('theme', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'alpha' => 'alpha (onepager)',
                    'beta' => 'beta (CV)',
                    'gamma' => 'gamma',
                ],
            ])

            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            // add language as choicetype, options are: en, hu, de, fr, es
            ->add('language', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'English' => 'en',
                    'Hungarian' => 'hu',
                    'German' => 'de',
                    'French' => 'fr',
                    'Spanish' => 'es',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
