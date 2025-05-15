<?php

namespace App\Form\Platform;

use App\Entity\Platform\BillingProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('zip')
            ->add('settlement')
            ->add('address')
            ->add('vat')
            ->add('euVat')
            ->add('billingRegistrationNumber')
            ->add('phoneNumber')
            ->add('faxNumber')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BillingProfile::class,
        ]);
    }
}
