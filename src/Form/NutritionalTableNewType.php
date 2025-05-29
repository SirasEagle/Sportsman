<?php

namespace App\Form;

use App\Entity\NutritionalTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NutritionalTableNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('kcal')
            ->add('fat')
            ->add('saturatedFat')
            ->add('carbohydrates')
            ->add('sugars')
            ->add('protein')
            ->add('salt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NutritionalTable::class,
        ]);
    }
}
