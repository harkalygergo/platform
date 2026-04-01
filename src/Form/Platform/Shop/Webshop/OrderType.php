<?php

namespace App\Form\Platform\Shop\Webshop;

use App\Entity\Platform\Order;
use App\Entity\Platform\Webshop\PaymentMethod;
use App\Enum\Platform\OrderStatusEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentInstance = $options['currentInstance'];

        $builder
            ->add('status', EnumType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => ' - status - ',
                'class' => OrderStatusEnum::class,
                'choice_label' => fn(OrderStatusEnum $status) => $this->translator->trans($status->label()),
                'required' => true,
            ])
            ->add('total', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('currency', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'HUF' => 'HUF',
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                    'JPY' => 'JPY',
                    'SEK' => 'SEK',
                    'CHF' => 'CHF',
                    'NOK' => 'NOK',
                    'DKK' => 'DKK',
                ]
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('shippingMethod', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('shippingAddress', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('billingVatNumber', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            /*
            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'choice_label' => 'name',
                'placeholder' => ' - payment method - ',
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($currentInstance) {
                    $qb = $er->createQueryBuilder('pm')
                        ->orderBy('pm.name', 'ASC');

                    if ($currentInstance !== null) {
                        $qb->andWhere('pm.instance = :instance')
                            ->setParameter('instance', $currentInstance);
                    }

                    return $qb;
                }
            ])
            */



            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'query_builder' => function ($repository) use ($currentInstance) {
                    return $repository->createQueryBuilder('w')
                        ->where('w.instance = :instance')
                        ->setParameter('instance', $currentInstance);
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])

            ->add('paymentStatus', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('billingCountry', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('billingZip', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('billingCity', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('billingAddress', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            // TODO add billing_profile_id
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'currentInstance' => null,
        ]);
    }
}
