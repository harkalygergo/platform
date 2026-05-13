<?php

namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuType extends AbstractType
{
    private ServiceEntityRepository $menuRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->menuRepository = new ServiceEntityRepository($registry, Menu::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentInstance = $options['currentInstance'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Cím',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control slugSource',
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-control slugTarget',
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
            ->add('parent', EntityType::class, [
                'class' => Menu::class,
                'choices' => $this->menuRepository->findBy(['instance' => $currentInstance]),
                'choice_label' => 'title',
                'label' => 'Szülő menü',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Státusz',
                'required' => false,
                'data' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'currentInstance' => null,
        ]);
    }
}
