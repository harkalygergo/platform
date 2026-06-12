<?php


namespace App\Form\Platform\Website;

use App\Entity\Platform\Media\Media;
use App\Entity\Platform\Website\WebsiteCategory;
use App\Entity\Platform\Website\WebsiteMedia;
use App\Entity\Platform\Website\WebsitePost;
use App\Repository\Platform\Media\MediaRepository;
use App\Repository\Platform\Website\WebsiteCategoryRepository;
use App\Repository\Platform\Website\WebsiteMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WebsitePostType extends AbstractType
{
    private $website;
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentWebsite = $this->website = $options['website'];
        $currentInstance = $currentWebsite->getInstance();

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
            ->add('leadDescription', TextType::class, [
                'label' => 'Lead',
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
            ])
            ->add('featuredImage', EntityType::class, [
                'class' => Media::class,
                'choice_label' => 'originalName', // Adjust to the property you want to display
                'query_builder' => function (MediaRepository $er) use ($currentInstance) {
                    return $er->createQueryBuilder('m')
                        ->where('m.instance = :instance')
                        ->setParameter('instance', $currentInstance);
                },
                'required' => false,
                'placeholder' => ' - featured image - ',
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
            ->add('saveAndDeploy', SubmitType::class, [
                'label' => '</> '.$this->translator->trans('action.save'). ' & deploy',
                'attr' => [
                    'class' => 'btn btn-outline-success my-3 ms-2',
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
