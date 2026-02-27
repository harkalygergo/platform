<?php

namespace App\Form\Platform\Ecom;

use App\Entity\Platform\Ecom\Product;
use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsiteMedia;
use App\Repository\Platform\Website\WebsiteMediaRepository;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentWebsite = $options['currentInstance']->getWebsites()->first();

        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control slugSource',
                ],
            ])
            ->add('slug', TextType::class, [
                'attr' => ['class' => 'form-control slugTarget'],
                'row_attr' => [
                    'data-prefix' => 'termek/',
                ],
            ])
            ->add('shortDescription', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('currency', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'HUF' => 'HUF',
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'Inaktív' => 0,
                    'Aktív' => 1,
                    'Vázlat' => 2,
                    'Archivált' => 3,
                ],
                'data' => 1,
            ])
            ->add('sku', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('salePrice', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('saleStartDate', DateTimeType::class, [
                'required' => false,
                'html5' => false,
                //'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => [
                    'class' => 'form-control datetimepicker',
                ],
            ])
            ->add('saleEndDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => ['class' => 'form-control datetimepicker'],
            ])
            ->add('costPrice', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('vatRate', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    0 => 0,
                    5 => 5,
                    18 => 18,
                    27 => 27,
                ],
                'data' => 27,
            ])
            ->add('quantity', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '0',
            ])
            ->add('minQuantity', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '0',
            ])
            ->add('maxQuantity', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '0',
            ])
            ->add('lowStockThreshold', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '0',
            ])
            ->add('trackInventory', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('allowBackorder', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('weight', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('width', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('height', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('depth', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('weightUnit', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'kg' => 'kg',
                    'g' => 'g',
                    'lb' => 'lb',
                    'oz' => 'oz',
                ],
                'placeholder' => ' - válassz - ',
                'empty_data' => 'kg',
            ])
            ->add('dimensionUnit', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'mm' => 'mm',
                    'cm' => 'cm',
                    'm' => 'm',
                    'in' => 'in',
                    'ft' => 'ft',
                ],
                'placeholder' => ' - válassz - ',
                'empty_data' => 'cm',
            ])
            ->add('isFeatured', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('isNew', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('metaTitle', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('metaDescription', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('sortOrder', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '0',
            ])

            // add multi select for current instance websites
            ->add('websites', EntityType::class, [
                'class' => Website::class,
                'choice_label' => function (Website $website) {
                    return $website->getName() . ' (' . $website->getDomain() . ')';
                },
                'query_builder' => function (WebsiteRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('w')
                        ->where('w.instance = :instance')
                        ->setParameter('instance', $options['currentInstance']);
                    return $qb->orderBy('w.name', 'ASC');
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Weboldalak',
            ])
            ->add('mainImage', EntityType::class, [
                'class' => WebsiteMedia::class,
                'choice_label' => 'originalName', // Adjust to the property you want to display
                'query_builder' => function (WebsiteMediaRepository $er) use ($currentWebsite) {
                    return $er->createQueryBuilder('wm')
                        ->where('wm.website = :website')
                        ->setParameter('website', $currentWebsite->getId());
                },
                'required' => false,
                'placeholder' => ' - select a main image - ',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'currentInstance' => null,
        ]);
    }
}
