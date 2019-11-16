<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Firstname')
            ->add('LastName')
            ->add('Position')
            ->add('Email')
            ->add('Phone')
            ->add('Mobile')
            ->add('Fax')
            ->add('IsPrincipal')
            ->add('ContentComment')
            ->add('City')
            ->add('Street')
            ->add('ZipCode')
            ->add('Country')
            ->add('AddressComment')
            ->add('AddressName')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
