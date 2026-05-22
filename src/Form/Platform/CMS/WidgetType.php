<?php

namespace App\Form\Platform\CMS;

use App\Entity\Platform\CMS\Widget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('templateCode', ChoiceType::class, [
                'placeholder' => ' - select template place - ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'header banner' => 'headerBanner',
                    'sidebar banner 1' => 'sidebarBanner1',
                    'sidebar banner 2' => 'sidebarBanner2',
                    'sidebar banner 3' => 'sidebarBanner3',
                    'footer - before' => 'footerBefore',
                    'footer - after' => 'footerAfter',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Név',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Név',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Tartalom',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Widget::class
        ]);
    }

}
