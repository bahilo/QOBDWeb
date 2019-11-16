<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Ref')
            ->add('Name')
            ->add('SellPrice')
            ->add('PurchasePrice')
            ->add('Stock')
            ->add('Picture')
            //->add('IsErasable')
            //->add('CreatedAt')
            ->add('ContentComment')
            ->add('Provider')
            ->add('ItemGroupe')
            ->add('ItemBrand')
            //->add('deliveries')
            //->add('orders')
            //->add('Tax')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
