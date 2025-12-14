<?php

namespace App\Form\Platform;

use App\Entity\Platform\Instance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
            ->add('intranet', TextType::class, [
                'label' => 'Intranet',
                'required' => false,
                'attr' => [
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
            ->add('status', CheckboxType::class, [
                'label' => 'Aktív',
                'required' => false,
                'attr' => [
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instance::class,
        ]);
    }
}
