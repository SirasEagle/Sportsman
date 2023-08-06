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

class WorkoutNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text', // Datepicker anzeigen
                'data' => new \DateTime(), // Hier wird das aktuelle Datum eingestellt
            ])
            ->add('info')
            ->add('isReal', CheckboxType::class, [
                'label' => 'Is it real?',
                'required' => false,
                'attr' => ['class' => 'abc'],
            ])
            ->add('user', ChoiceType::class, [
                'choices' => [
                    'Adrian' => 0,
                    'Angelina' => 1,
                ],
                'expanded' => true, // Anzeigen als Radio-Buttons
                'label' => 'Choose user:',
                'attr' => ['class' => 'abc'],
                'mapped' => false
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
            'data_class' => Workout::class,
        ]);
    }
}
