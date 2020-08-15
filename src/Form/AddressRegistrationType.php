<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Country;
use App\Form\PaysRegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('Country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'Name'
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
