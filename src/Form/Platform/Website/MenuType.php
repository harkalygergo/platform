<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Cím',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // add position field with integer type
            ->add('position', IntegerType::class, [
                'label' => 'Pozíció',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('parent', null, [
                'label' => 'Szülő menü',
                'required' => false,
                'placeholder' => 'Nincs szülő',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Státusz',
                'required' => false,
                'data' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
