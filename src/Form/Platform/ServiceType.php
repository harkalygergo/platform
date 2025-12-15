<?php

namespace App\Form\Platform;

use App\Entity\Platform\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    // create form for newsletter
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Név',
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
            ->add('type', TextType::class, [
                'label' => 'Típus',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Leírás',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('fee', TextType::class, [
                'label' => 'Díj',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('currency', TextType::class, [
                'label' => 'Pénznem',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Aktív',
                'required' => false,
                'attr' => [
                ],
            ])
        ;
    }

    public function validateSendAt(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
