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
            ->add('FirstName')
            ->add('LastName')
            ->add('CompanyName')
            ->add('Email')
            ->add('Phone')
            ->add('Fax')
            ->add('Rib')
            ->add('CRN')
            ->add('PayDelay')
            ->add('MaxCredit')
            ->add('Denomination')
            ->add('City')
            //->add('IsProspect')
            ->add('Street')
            ->add('ZipCode')
            ->add('Country')
            ->add('ClientComment')
            ->add('AddressComment')
            //->add('AddressName')
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
