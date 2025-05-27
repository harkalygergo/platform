<?php

namespace App\Form\Platform\Task;

use App\Entity\Platform\Task\Task;
use App\Entity\Platform\User;
use App\Repository\Platform\UserRepository;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    private EntityManagerInterface $entityManager;
    private $currentInstance;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        //$this->currentInstance = $currentInstance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summernote'
                ]
            ])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Low' => 1,
                    'Medium' => 2,
                    'High' => 3,
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getFullName() . ' (' . $user->getEmail().')';
                },

                    //'email', // or any other field you want to display
                'query_builder' => function (UserRepository $userRepository) use ($options) {
                    $qb = $userRepository->createQueryBuilder('u');

                    if ($options['currentInstance'] !== null) {
                        $qb
                            ->andWhere(':instance MEMBER OF u.instances')
                            ->setParameter('instance', $options['currentInstance']);
                    }

                    return $qb->orderBy('u.email', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control'
                ],
                //'placeholder' => 'Choose an assignee',
                'required' => true
            ])





            /*
            ->add('deadline', DateTimeImmutableType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            */
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Open' => 1,
                    'In Progress' => 2,
                    'Done' => 3,
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'currentInstance' => null,
        ]);

        // Validate that the option is a boolean or null
        //$resolver->setAllowedTypes('include_inactive', ['bool', 'null']);
    }
}
