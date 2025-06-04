<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Form\MultiplierType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('muscleGroup', EntityType::class, [
                'label' => 'exercise.muscle_group',
                'class' => MuscleGroup::class,
                'choice_label' => 'term', // This is the property of MuscleGroup to display as option label
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'exercise.description',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('imageLink', TextType::class, [
                'label' => 'Link zu beschreibendem Bild:',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('musicLink', TextType::class, [
                'label' => 'Link zum Lied / Workoutvideo:',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('musicLinkIframe', TextType::class, [
                'label' => 'Link zu Lied (iframe):',
                'required' => false,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('usesWeight', CheckboxType::class, [
                'label' => 'exercise.uses_weight',
                'required' => false,
                'row_attr' => ['class' => 'mg-add-check'],
                'data'     => false,
            ])
            ->add('isSingleUnit', CheckboxType::class, [
                'label' => 'exercise.is_single_unit',
                'required' => false,
                'row_attr' => ['class' => 'mg-add-check'],
                'data'     => false,
            ])
            ->add('multiplier', MultiplierType::class, [
                'label' => false, // no label for this container
                'mapped' => true,
                'required' => true,
                'empty_data' => null,
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
