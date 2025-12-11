<?php

namespace App\Form\Platform\Popup;

use App\Entity\Platform\Popup\Popup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PopupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('modalTitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('modalBody', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('modalFooter', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('maximumAppearance', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('css', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('js', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Popup::class,
        ]);
    }
}
