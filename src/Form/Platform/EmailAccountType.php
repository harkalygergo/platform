<?php

namespace App\Form\Platform;

use App\Entity\Platform\EmailAccount;
use App\Entity\Platform\Service;
use App\Repository\Platform\ServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailAccountType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'Status',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('prefix', TextType::class, [
                'label' => 'Prefix',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            // add Service list
            // add multi select for current instance websites
            ->add('service', EntityType::class, [
                'class' => Service::class,
                /*
                'choice_label' => function (Service $website) {
                    return $website->getName() . ' (' . $website->getDomain() . ')';
                },
                */
                'query_builder' => function (ServiceRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('w')
                        ->where('w.instance = :instance')
                        ->andWhere('w.type = :type')
                        ->setParameter('instance', $options['currentInstance'])
                        ->setParameter('type', 'domain')
                    ;
                    return $qb->orderBy('w.name', 'ASC');
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Domain',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailAccount::class,
            'currentInstance' => null,
        ]);
    }
}
