<?php

namespace App\Form\Platform;

use App\Entity\Platform\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClientType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('namePrefix', TextType::class, [
                'label' => $this->translator->trans('user.namePrefix'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', TextType::class, [
                'label' => $this->translator->trans('user.lastName'),
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('middleName', TextType::class, [
                'label' => $this->translator->trans('user.middleName'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('firstName', TextType::class, [
                'label' => $this->translator->trans('user.firstName'),
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('birthDate', DateType::class, [
                'label' => $this->translator->trans('user.birthDate'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phone', TextType::class, [
                'label' => $this->translator->trans('user.phone'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('country', TextType::class, [
                'label' => $this->translator->trans('address.country'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('zip', TextType::class, [
                'label' => $this->translator->trans('address.zip'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('settlement', TextType::class, [
                'label' => $this->translator->trans('address.settlement'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('address', TextType::class, [
                'label' => $this->translator->trans('address.address'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('comment', TextareaType::class, [
                'label' => $this->translator->trans('global.comment'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
