<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\AddressRegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Firstname', TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('LastName', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('Position', TextType::class,[
                'label' => 'Poste'
            ])
            ->add('Email')
            ->add('Phone', TextType::class,[
                'label' => 'Téléphone fixe'
            ])
            ->add('Mobile', TextType::class,[
                'label' => 'Téléphone mobile'
            ])
            //->add('Fax')
            //->add('IsPrincipal')
            ->add('Comment', CommentRegistrationType::class, [
                'label' => false
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
