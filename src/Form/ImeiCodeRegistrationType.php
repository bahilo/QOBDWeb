<?php

namespace App\Form;

use App\Entity\EanCode;
use App\Entity\ImeiCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImeiCodeRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Code', null, [
                'label' => 'Code IMEI'
            ])
            ->add('SerieCode', null, [
                'label' => 'N° de serie du produit'
            ])
            ->add('EanCode', null, [
                'label' => 'Code EAN'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImeiCode::class,
        ]);
    }
}
