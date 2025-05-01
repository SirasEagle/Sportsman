<?php

namespace App\Form;

use App\Entity\Multiplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addition', IntegerType::class, [
                'label' => 'multiplier.addition',
                'required' => true,
                'attr' => ['min' => 0, 'class' => 'abc'],
                'row_attr' => ['class' => 'number-input'],
            ])
            ->add('multiplyBy', IntegerType::class, [
                'label' => 'multiplier.multiply_by',
                'required' => true,
                'attr' => ['min' => 0, 'class' => 'abc'],
                'row_attr' => ['class' => 'number-input'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Multiplier::class,
            'translation_domain' => 'forms',
        ]);
    }
}
