<?php

namespace App\Form;

use App\Entity\Platform\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control slugSource',
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control slugTarget',
                ],
            ])
            ->add('leadDescription', TextType::class, [
                'label' => 'Lead',
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add("locationName", TextType::class, [
                'label' => 'Location Name',
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('startAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Start',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('endAt', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'End',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('ticketUrl', TextType::class, [
                'required' => false,
                'label' => 'Ticket URL',
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
            ->add('imageUrl', TextType::class, [
                'required' => false,
                'label' => 'Image URL',
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

