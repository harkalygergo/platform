<?php

namespace App\Form\Platform\Webshop;

use App\Entity\Platform\Webshop\PaymentMethod;
use App\Enum\Platform\PaymentMethodTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('type', ChoiceType::class, [
                'label' => 'Payment Method Type',
                'placeholder' => 'Select a type',
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => PaymentMethodTypeEnum::TYPES,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'label' => 'Status',
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $paymentMethod = $event->getData();
            $form = $event->getForm();

            if ($paymentMethod && $paymentMethod->getId() !== null && $paymentMethod->getType() === 'card') {
                $form
                    ->add('code', ChoiceType::class, [
                        'required' => true,
                        'attr' => [
                            'class' => 'form-control',
                        ],
                        'choices' => PaymentMethodTypeEnum::CODES,
                    ])
                    ->add('cardStatus', ChoiceType::class, [
                        'choices' => [
                            'Test' => false,
                            'Live' => true,
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ],
                    ])
                    ->add('cardBaseUrlTest', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardCustomerTest', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardTerminalTest', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardUsernameTest', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardPasswordTest', PasswordType::class, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardBaseUrlLive', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardCustomerLive', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardTerminalLive', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardUsernameLive', null, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                    ->add('cardPasswordLive', PasswordType::class, [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ])
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }

}
