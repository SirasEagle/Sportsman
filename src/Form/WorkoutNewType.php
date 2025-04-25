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
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text', // Datepicker anzeigen
                'data' => new \DateTime(), // Hier wird das aktuelle Datum eingestellt
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('info', TextType::class, [
                'label' => 'Information',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('isReal', CheckboxType::class, [
                'label' => 'Is it real?',
                'required' => false,
                'row_attr' => ['class' => 'mg-add-check'],
            ])
            ->add('user', ChoiceType::class, [
                'choices' => [
                    'Adrian' => 1,
                    'Angelina' => 2,
                ],
                'expanded' => true, // Anzeigen als Radio-Buttons
                'label' => 'Choose user:',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Speichern',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workout::class,
        ]);
    }
}
