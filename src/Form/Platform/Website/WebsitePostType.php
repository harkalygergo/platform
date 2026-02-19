<?php


namespace App\Form\Platform\Website;

use App\Entity\Platform\Website\WebsiteCategory;
use App\Entity\Platform\Website\WebsiteMedia;
use App\Entity\Platform\Website\WebsitePost;
use App\Repository\Platform\Website\WebsiteCategoryRepository;
use App\Repository\Platform\Website\WebsiteMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsitePostType extends AbstractType
{
    private $website;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentWebsite = $this->website = $options['website'];

        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control slugSource',
                ],
            ])
            ->add('slug', TextType::class, [
                'attr' => [
                    'class' => 'form-control slugTarget',
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
            ->add('metaKeywords', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('featuredImage', EntityType::class, [
                'class' => WebsiteMedia::class,
                'choice_label' => 'originalName', // Adjust to the property you want to display
                'query_builder' => function (WebsiteMediaRepository $er) use ($currentWebsite) {
                    return $er->createQueryBuilder('wm')
                        ->where('wm.website = :website')
                        ->setParameter('website', $currentWebsite->getId());
                },
                'required' => false,
                'placeholder' => ' - select a featured image - ',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => WebsiteCategory::class,
                /**
                 * @var WebsiteCategory $category
                 */
                'choice_label' => function ($category) {
                    return $category->getTitle() . ' (' . $category->getSlug() . ')';
                },

                'query_builder' => function (WebsiteCategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c')
                        ->where('c.website = :website')
                        ->setParameter('website', $this->website);
                    return $qb->orderBy('c.title', 'ASC');
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                //'placeholder' => 'Choose an assignee',
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summernote',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebsitePost::class,
            'website' => null,
        ]);
    }

}
