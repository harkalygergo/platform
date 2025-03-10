<?php

namespace App\Form\Platform\Popup;

use App\Entity\Platform\Popup\Popup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PopupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('modalTitle')
            ->add('modalBody')
            ->add('modalFooter')
            ->add('maximumAppearance')
            ->add('css')
            ->add('js')
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
