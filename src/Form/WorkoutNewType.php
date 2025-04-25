<?php

namespace App\Form;

use App\Entity\Workout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class WorkoutNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO: nicht 1/2
        $builder
            ->add('date', DateType::class, [
                'label' => 'workout_new.date',
                'widget' => 'single_text', // Datepicker anzeigen
                'data' => new \DateTime(), // Hier wird das aktuelle Datum eingestellt
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('info', TextType::class, [
                'label' => 'workout_new.info',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('isReal', CheckboxType::class, [
                'label' => 'workout_new.is_real',
                'required' => false,
                'row_attr' => ['class' => 'mg-add-check'],
            ])
            ->add('user', ChoiceType::class, [
                'label' => 'workout_new.user',
                'choices' => [
                    'Adrian' => 1,
                    'Angelina' => 2,
                ],
                'expanded' => true, // Anzeigen als Radio-Buttons
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'workout_new.submit',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workout::class,
            'translation_domain' => 'forms',
        ]);
    }
}
