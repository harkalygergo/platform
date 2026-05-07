<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Media\Media;
use App\Entity\Platform\Template;
use App\Entity\Platform\Website\CmsPage;
use App\Entity\Platform\Website\Website;
use App\Repository\Platform\Media\MediaRepository;
use App\Repository\Platform\Website\CmsPageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WebsiteType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentWebsite = $options['data'] ?? null; // Get the current Website entity
        $currentInstance = $options['currentInstance'];

        $builder
            ->add('domain', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Domain',
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $this->translator->trans('global.name'),
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $this->translator->trans('global.title'),
            ])
            ->add('slogan', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $this->translator->trans('global.slogan'),
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $this->translator->trans('data.description'),
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $this->translator->trans('user.phone'),
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
                'label' => $this->translator->trans('address.address'),
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
            ->add('threads', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('bluesky', TextType::class, [
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
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'required' => false,
                'label_html' => true,
                'expanded' => true,
                'placeholder' => ' - choose - ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choice_label' => function (Template $template) {
                    return $template->getName() . ' - <small><i>' . $template->getDescription().'</i></small>';
                },
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.isActive = :active')
                        ->setParameter('active', true);
                },
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
            ->add('googleApiKey', TextType::class, [
                'label' => 'Google API Key',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;

        if ($currentWebsite) {
            $builder
                ->add('termsAndConditions', EntityType::class, [
                    'class' => CmsPage::class,
                    'choice_label' => 'title',
                    'required' => false,
                    'query_builder' => function (CmsPageRepository $repo) use ($currentWebsite) {
                        return $repo->createQueryBuilder('p')
                            ->where('p.instance = :instance')
                            ->setParameter('instance', $currentWebsite->getInstance());
                    },
                ])
                ->add('favicon', EntityType::class, [
                    'class' => Media::class,
                    'choice_label' => 'originalName', // Adjust to the property you want to display
                    'query_builder' => function (MediaRepository $er) use ($currentInstance) {
                        return $er->createQueryBuilder('wm')
                            ->where('wm.instance = :instance')
                            ->setParameter('instance', $currentInstance);
                    },
                    'required' => false,
                    'placeholder' => 'Select a favicon',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('logo', EntityType::class, [
                    'class' => Media::class,
                    'choice_label' => 'originalName', // Adjust to the property you want to display
                    'query_builder' => function (MediaRepository $er) use ($currentInstance) {
                        return $er->createQueryBuilder('wm')
                            ->where('wm.instance = :instance')
                            ->setParameter('instance', $currentInstance);
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
            'currentInstance' => null,
        ]);
    }
}
