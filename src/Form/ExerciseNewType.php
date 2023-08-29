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
                'label' => 'Name:',
                'attr' => ['class' => 'abc'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'attr' => ['class' => 'abc'],
            ])
            ->add('muscleGroup', EntityType::class, [
                'label' => 'Muskelgruppe:',
                'class' => MuscleGroup::class,
                'choice_label' => 'term', // This is the property of MuscleGroup to display as option label
                'attr' => ['class' => 'abc'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'abc'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
