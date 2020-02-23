<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('Short')
            ->add('Content')
            ->add('PublishAt')
            ->add('PublishEndAt')
            ->add('CreatedAt')
            ->add('IsCenterOfInterest')
            ->add('IsProduct')
            ->add('IsPartenaire')
            ->add('IsTeam')
            ->add('IsTestimony')
            ->add('Role')
            ->add('Name')
            ->add('Picture')
            ->add('Author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
