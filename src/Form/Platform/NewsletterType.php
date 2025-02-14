<?php

namespace App\Form\Platform;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsletterType extends AbstractType
{
    // create form for newsletter
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('plainTextContent', TextareaType::class, [
                'label' => 'Plain text content',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('htmlContent', TextareaType::class, [
                'label' => 'HTML content',
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            /*
            ->add('instance', EntityType::class, [
                'class' => Instance::class,
                'label' => 'Instance',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            */
        ;
    }
}
