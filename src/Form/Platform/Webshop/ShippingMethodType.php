<?php

namespace App\Form\Platform\Webshop;

use App\Entity\Platform\Webshop\ShippingMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add('fee', MoneyType::class, [
                'label' => 'Fee',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
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
            ])
        ;
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
            'DHL' => 'dhl',
            'DPD' => 'dpd',
            'FámaFutár' => 'fama_futar',
            'FedEx' => 'fedex',
            'Foxpost' => 'foxpost',
            'GLS automata' => 'gls_automata',
            'GLS csomagpont' => 'gls_csomagpont',
            'GLS házhoz szállítás' => 'gls_hazhoz_szallitas',
            'Local Pickup' => 'local_pickup',
            'MPL' => 'mpl',
            'Other' => 'other',
            'Packeta' => 'packeta',
            'Posta.hu' => 'posta_hu',
            'UPS' => 'ups',
            'USPS' => 'usps',
        ];
    }
}
