<?php

namespace App\Form\Platform\Media;

use App\Entity\Platform\Media\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends AbstractType
{
    public function buildForm($builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Upload File(s)',
                'multiple' => true,
                'mapped' => false, // This field is not mapped to the Media entity
                'required' => true,
            ])

            ->add('description', TextType::class, [
                'label' => 'Leírás',
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
        ;
    }

    public function configureOptions($resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
