<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('City', null, [
                'label' => 'Ville'
            ])
            ->add('Street', null, [
                'label' => 'Rue'
            ])
            ->add('ZipCode', null, [
                'label' => 'Code postale'
            ])
            ->add('Country', null, [
                'label' => 'Pays'
            ])
            ->add('Comment', CommentRegistrationType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            "allow_extra_fields" => true,
        ]);
    }
}
