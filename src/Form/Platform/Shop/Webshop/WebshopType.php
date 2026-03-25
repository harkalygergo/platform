<?php

namespace App\Form\Platform\Shop\Webshop;

use App\Entity\Platform\Media\Media;
use App\Entity\Platform\Template;
use App\Entity\Platform\Webshop\Webshop;
use App\Repository\Platform\Media\MediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentInstance = $options['currentInstance'];

        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
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
            ->add('theme', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'alpha (onepager)' => 'alpha',
                    'beta (CV)' => 'beta',
                    'gamma' => 'gamma',
                    '01. Alpha (empty)' => '1_alpha',
                    '02. Beta (head)' => '2_beta',
                    '03. Gamma (with sidebar)' => '3_gamma',
                    '04. Delta (events)' => '4_delta',
                    '05. Epsilon' => '5_epsilon',
                    '06. Zeta' => '6_zeta',
                    '07. Eta' => '7_eta',
                    '08. Theta' => '8_theta',
                    '09. Iota' => '9_iota',
                    '10. Kappa' => '10_kappa',
                    '11. Lambda' => '11_lambda',
                    '12. Mu' => '12_mu',
                    '13. Nu' => '13_nu',
                    '14. Xi' => '14_xi',
                    '15. Omicron' => '15_omicron',
                    '16. Pi' => '16_pi',
                    '17. Rho' => '17_rho',
                    '18. Sigma' => '18_sigma',
                    '19. Tau' => '19_tau',
                    '20. Upsilon' => '20_upsilon',
                    '21. Phi' => '21_phi',
                    '22. Chi' => '22_chi',
                    '23. Psi' => '23_psi',
                    '24. Omega' => '24_omega',
                ],
            ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'required' => false,
                'placeholder' => ' - choose - ',
                'attr' => [
                    'class' => 'form-control',
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
            ->add('googleApiKey', TextType::class, [
                'label' => 'Google API Key',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('favicon', EntityType::class, [
                'class' => Media::class,
                'choice_label' => 'originalName', // Adjust to the property you want to display
                'query_builder' => function (MediaRepository $er) use ($currentInstance) {
                    return $er->createQueryBuilder('m')
                        ->where('m.instance = :instance')
                        ->setParameter('instance', $currentInstance);
                },
                'required' => false,
                'placeholder' => ' - favicon - ',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('logo', EntityType::class, [
                'class' => Media::class,
                'choice_label' => 'originalName', // Adjust to the property you want to display
                'query_builder' => function (MediaRepository $er) use ($currentInstance) {
                    return $er->createQueryBuilder('m')
                        ->where('m.instance = :instance')
                        ->setParameter('instance', $currentInstance);
                },
                'required' => false,
                'placeholder' => ' - logo - ',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Webshop::class,
            'currentInstance' => null,
        ]);
    }
}
