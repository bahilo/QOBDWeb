<?php

namespace App\Form;

use App\Entity\Client;
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
