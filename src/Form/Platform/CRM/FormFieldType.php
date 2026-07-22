<?php

namespace App\Form\Platform\CRM;

use App\Entity\Platform\CRM\FormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFieldType extends AbstractType
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
            ->add('name', TextType::class, [
                'label' => 'Név',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('label', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'text'     => 'text',
                    'number' => 'number',
                    'email'    => 'email',
                    'textarea' => 'textarea',
                    'date'     => 'date',
                    'select'    => 'select',
                    'checkbox' => 'checkbox',
                    'radio'    => 'radio',
                    //'image'    => 'image',
                    //'file'     => 'file',
                ]
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Pozíció',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('isRequired', CheckboxType::class, [
                'label' => 'Required?',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('defaultValue', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control
                    ',
                ],
            ])
            ->add('placeholder', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control
                    ',
                ],
            ])
            ->add('helpText', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control
                    ',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormField::class
        ]);
    }

}
