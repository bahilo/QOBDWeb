<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('FirstName')
            ->add('LastName')
            ->add('Phone')
            ->add('Fax')
            ->add('Email')
            ->add('UserName')
            ->add('Password', PasswordType::class)
            ->add('Confirme_password', PasswordType::class)
            ->add('Picture')
            //->add('IsAdmin')
            //->add('IsOnline')
            ->add('ListSize')
            ->add('IsActivated')
            //->add('IPAddress')
            //->add('Roles')
            //->add('messages')
            //->add('Discussion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
