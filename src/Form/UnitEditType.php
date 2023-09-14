<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UnitEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('exerciseId')
            // ->add('workoutId')
            ->add('set1')
            ->add('set2')
            ->add('set3')
            ->add('info')
            ->add('exercise')
            ->add('exercise', EntityType::class, [
                'label' => 'Übung:',
                'class' => Exercise::class,
                'choice_label' => 'name', // This is the property of MuscleGroup to display as option label
                'attr' => ['class' => 'abc'],
            ])
            // ->add('workout')
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'abc'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
        ]);
    }
}
