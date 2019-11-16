<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AgentRegistrationType extends AbstractType
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
            ->add('PlainTextPassword', PasswordType::class, ['required'   => false])
            ->add('Confirme_password', PasswordType::class, ['required'   => false])
            ->add('Picture')
            ->add('ListSize')
            ->add('IsActivated')
            //->add('IsAdmin')
            //->add('IsOnline')
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
