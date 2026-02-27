<?php

namespace App\Form\Platform\Ecom;

use App\Entity\Platform\Ecom\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoryType extends AbstractType
{
    private ServiceEntityRepository $productCategoryRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->productCategoryRepository = new ServiceEntityRepository($registry, ProductCategory::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentInstance = $options['currentInstance'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Név',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Leírás',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('parentCategory', EntityType::class, [
                'class' => ProductCategory::class,
                'choices' => $this->productCategoryRepository->findBy(['instance' => $currentInstance]),
                'choice_label' => 'name',
                'label' => 'Szülő kategória',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCategory::class,
            'currentInstance' => null,
        ]);
    }
}
