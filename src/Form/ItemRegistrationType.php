<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\EanCode;
use App\Entity\ImeiCode;
use App\Entity\Provider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
            ->add('ImeiCode', ImeiCodeRegistrationType::class)
            ->add('Comment', CommentRegistrationType::class)
            ->add('Provider')
            ->add('ItemGroupe')
            ->add('ItemBrand')
            ->add('PictureFile', FileType::class, [
                'label' => 'avatar',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez renseigner un format de fichier valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            "allow_extra_fields" => true,
        ]);
    }
}
