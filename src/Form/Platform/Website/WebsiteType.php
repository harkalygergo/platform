<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsiteMedia;
use App\Repository\Platform\Website\WebsiteMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentWebsite = $options['data'] ?? null; // Get the current Website entity

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
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('slogan', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('facebook', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('twitter', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('linkedin', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('youtube', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('tiktok', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
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
            ->add('metaDescription', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaKeywords', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaAuthor', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaRobots', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'index, follow' => 'index,follow',
                    'noindex, nofollow' => 'noindex,nofollow',
                    'index, nofollow' => 'index,nofollow',
                    'noindex, follow' => 'noindex,follow',
                ],
            ])
            ->add('theme', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'alpha (onepager)' => 'alpha',
                    'beta (CV)' => 'beta',
                    'gamma' => 'gamma',
                ],
            ])
            ->add('FTPHost', TextType::class, [
                'label' => 'FTP host',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPUser', TextType::class, [
                'label' => 'FTP user',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPPassword', PasswordType::class, [
                'label' => 'FTP password',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('FTPPath', TextType::class, [
                'label' => 'FTP path',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('headerCSS', TextareaType::class, [
                'label' => 'Header CSS',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('headerJS', TextareaType::class, [
                'label' => 'Header JS',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('headerHTML', TextareaType::class, [
                'label' => 'Header HTML',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('bodyTopHTML', TextareaType::class, [
                'label' => 'Body Top HTML',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('footerJS', TextareaType::class, [
                'label' => 'Footer JS',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('footerHTML', TextareaType::class, [
                'label' => 'Footer HTML',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
        ;

        if ($currentWebsite) {
            $builder
                ->add('favicon', EntityType::class, [
                    'class' => WebsiteMedia::class,
                    'choice_label' => 'originalName', // Adjust to the property you want to display
                    'query_builder' => function (WebsiteMediaRepository $er) use ($currentWebsite) {
                        return $er->createQueryBuilder('wm')
                            ->where('wm.website = :website')
                            ->setParameter('website', $currentWebsite->getId());
                    },
                    'required' => false,
                    'placeholder' => 'Select a favicon',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('logo', EntityType::class, [
                    'class' => WebsiteMedia::class,
                    'choice_label' => 'originalName', // Adjust to the property you want to display
                    'query_builder' => function (WebsiteMediaRepository $er) use ($currentWebsite) {
                        return $er->createQueryBuilder('wm')
                            ->where('wm.website = :website')
                            ->setParameter('website', $currentWebsite->getId());
                    },
                    'required' => false,
                    'placeholder' => 'Select a logo',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Website::class,

        ]);
    }
}
