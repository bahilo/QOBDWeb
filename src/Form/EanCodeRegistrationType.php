<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Currency;
use App\Entity\EanCode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EanCodeRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Code')
            ->add('Country', EntityType::class,[
                'class' => Country::class,
                'choice_label' => 'Name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EanCode::class,
            "allow_extra_fields" => true,
        ]);
    }
}
