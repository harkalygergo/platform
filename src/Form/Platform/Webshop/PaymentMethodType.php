<?php

namespace App\Form\Platform\Webshop;

use App\Entity\Platform\Webshop\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Payment Method Name',
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
                'choices' => PaymentMethodType::getChoices(),
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
            'data_class' => PaymentMethod::class,
        ]);
    }

    public static function getChoices(): array
    {
        return [
            'Credit Card' => 'credit_card',
            'PayPal' => 'paypal',
            'Bank Transfer' => 'bank_transfer',
            'Cash on Delivery' => 'cash_on_delivery',
        ];
    }
}
