<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'exercise.name',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'exercise.description',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('muscleGroup', EntityType::class, [
                'label' => 'exercise.muscle_group',
                'class' => MuscleGroup::class,
                'choice_label' => 'term', // This is the property of MuscleGroup to display as option label
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'exercise.submit',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
            'translation_domain' => 'forms',
        ]);
    }
}
