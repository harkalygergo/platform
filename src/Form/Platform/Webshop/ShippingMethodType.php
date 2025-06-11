<?php

namespace App\Form\Platform\Webshop;

use App\Entity\Platform\Webshop\ShippingMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Shipping Method Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'label' => 'Status',
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => ShippingMethodType::getChoices(),
                'label' => 'Payment Method Type',
                'placeholder' => 'Select a type',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShippingMethod::class,
        ]);
    }

    public static function getChoices(): array
    {
        return [
            'GLS automata' => 'gls_automata',
            'GLS csomagpont' => 'gls_csomagpont',
            'GLS házhoz szállítás' => 'gls_hazhoz_szallitas',
            'DPD' => 'dpd',
            'FedEx' => 'fedex',
            'UPS' => 'ups',
            'USPS' => 'usps',
            'DHL' => 'dhl',
            'Local Pickup' => 'local_pickup',
            'Other' => 'other',
            'Foxpost' => 'foxpost',
            'Posta.hu' => 'posta_hu',
            'MPL' => 'mpl',
            'Packeta' => 'packeta',
        ];
    }
}
