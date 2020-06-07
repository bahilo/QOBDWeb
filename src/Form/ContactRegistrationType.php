<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\AddressRegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            //->add('Fax')
            //->add('IsPrincipal')
            ->add('Comment', CommentRegistrationType::class, [
                'label' => 'Commentaire'
            ])
            ->add('Address', AddressRegistrationType::class)
            // ->add('Street')
            // ->add('ZipCode')
            // ->add('Country')
            // ->add('AddressComment')
            //->add('AddressName')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            "allow_extra_fields" => true,
        ]);
    }
}
