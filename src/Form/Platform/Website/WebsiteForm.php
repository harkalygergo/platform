<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('FTPHost')
            ->add('FTPUser')
            ->add('FTPPassword')
            ->add('FTPPath')
            ->add('domain')
            ->add('name')
            ->add('description')
            ->add('theme')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
