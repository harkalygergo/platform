<?php

namespace App\Form\Platform\CRM;

use App\Entity\Platform\CRM\Testimonial;
use App\Entity\Platform\Media\Media;
use App\Repository\Platform\Media\MediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
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
            ->add('title', TextType::class, [
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

        if(array_key_exists('data', $options)) {
            $instance = $options['data']->getInstance();

            $builder
                ->add('mainImage', EntityType::class, [
                    'class' => Media::class,
                    'choice_label' => 'originalName', // Adjust to the property you want to display
                    'query_builder' => function (MediaRepository $er) use ($instance) {
                        return $er->createQueryBuilder('m')
                            ->where('m.instance = :instance')
                            ->setParameter('instance', $instance);
                    },
                    'required' => false,
                    'placeholder' => ' - select a featured image - ',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
            ;
        }


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class
        ]);
    }

}
