<?php

namespace App\Form\Platform\CMS;

use App\Entity\Platform\CMS\Widget;
use App\Enum\Platform\WidgetTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
            ->add('templateCode', EnumType::class, [
                'class'       => WidgetTypeEnum::class,
                'placeholder' => ' - select template place - ',
                'attr'        => ['class' => 'form-control'],
                'choice_label' => fn(WidgetTypeEnum $e) => $e->label(),
                'required'    => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Név',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Leírás',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Tartalom',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                    'rows' => 10
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
