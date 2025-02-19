<?php

namespace App\Form\Platform\Newsletter;

use App\Entity\Platform\Newsletter\NewsletterSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterSettingsType extends AbstractType
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromName', TextType::class, [
                'label' => 'from name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('fromEmail', EmailType::class, [
                'label' => 'from email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('defaultSubject', TextType::class, [
                'label' => 'default subject',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('defaultPlainTextContent', TextareaType::class, [
                'label' => 'Default plain text content',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('defaultHtmlContent', TextareaType::class, [
                'label' => 'Default HTML content',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add('defaultFooter', TextareaType::class, [
                'label' => 'Default footer',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewsletterSettings::class,
        ]);
    }
}
