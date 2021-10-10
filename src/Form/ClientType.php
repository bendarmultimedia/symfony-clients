<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\ClientKind;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'required' => true,
                'attr'  => [
                    'class' => 'form-control rounded'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'required' => true,
                'attr'  => [
                    'class' => 'form-control rounded'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr'  => [
                    'class' => 'form-control rounded'
                ]
            ])
            ->add('kind', EntityType::class, [
                'class' => ClientKind::class,
                'label' => 'kind',
                'translation_domain' => 'messages',
                'choice_label' => 'name',
                'multiple' => false,
                'empty_data' => null,
                'placeholder' => 'Select client kind',
                'attr'  => [
                    'class' => 'selectpicker form-control rounded',
                    "data-live-search"  =>   "true"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
