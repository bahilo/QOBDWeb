<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Contact;
use App\Form\ContactRegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('CompanyName')
            ->add('Rib')
            ->add('CRN')
            ->add('PayDelay')
            ->add('MaxCredit')
            ->add('Denomination')
            ->add('contactPrincipal', ContactRegistrationType::class)
            ->add('Comment', CommentRegistrationType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            "allow_extra_fields" => true,
        ]);
    }
}
