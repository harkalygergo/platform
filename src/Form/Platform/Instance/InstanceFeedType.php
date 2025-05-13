<?php

namespace App\Form\Platform\Instance;

use App\Entity\Platform\Instance\InstanceFeed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class InstanceFeedType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => $this->translator->trans('global.message'),
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Message cannot be blank']),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Message must be at least {{ limit }} characters long',
                        'maxMessage' => 'Message cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            // add submit
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('action.save'),
                'attr' => ['class' => 'm-2 btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InstanceFeed::class,
        ]);
    }
}
