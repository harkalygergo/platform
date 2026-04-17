<?php

namespace App\Form\Platform\Order;

use App\Enum\Platform\OrderStatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderEmailTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderStatus', ChoiceType::class, [
                'label' => 'Order status',
                'choices' => array_combine(
                    array_map(fn($status) => $status->label(), OrderStatusEnum::cases()),
                    OrderStatusEnum::cases()
                ),
                'choice_label' => fn($choice) => $choice->label(),
                'choice_value' => fn($choice) => $choice?->value,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('plainTextContent', TextareaType::class, [
                'label' => 'Plain text content',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('htmlContent', TextareaType::class, [
                'label' => 'HTML content',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
        ;
    }
}
