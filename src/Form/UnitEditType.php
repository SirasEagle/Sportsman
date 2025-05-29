<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UnitEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('set1', IntegerType::class, [
                'label'    => 'unit.set1',
                'attr'     => [
                    'class' => 'abc',
                    'min'   => 0, // lowest number
                    'step'  => 1, // increase per step
                ],
                'row_attr' => ['class' => 'number-input'],
            ])
            ->add('set2', IntegerType::class, [
                'label'    => 'unit.set2',
                'attr'     => [
                    'class' => 'abc',
                    'min'   => 0, // lowest number
                    'step'  => 1, // increase per step
                ],
                'row_attr' => ['class' => 'number-input'],
            ])
            ->add('set3', IntegerType::class, [
                'label'    => 'unit.set3',
                'attr'     => [
                    'class' => 'abc',
                    'min'   => 0, // lowest number
                    'step'  => 1, // increase per step
                ],
                'row_attr' => ['class' => 'number-input'],
            ])
        ;

        // Dynamisch das Weight-Feld nur hinzufügen, wenn die zugehörige Exercise usesWeight == true
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Unit|null $unit */
            $unit = $event->getData();
            $form = $event->getForm();

            if (null === $unit || null === $unit->getExercise()) {
                return;
            }

            if ($unit->getExercise()->getUsesWeight()) {
                $form->add('weight', NumberType::class, [
                    'label'    => 'exercise.weight.kg',
                    'required' => true,
                    'mapped' => false,
                    'data'     => $unit->getWeight(),
                    'scale'    => 1,
                    'attr'     => [
                        'min'  => 0,
                        'step' => 0.1,
                        'class' => 'abc',
                    ],
                    'row_attr' => ['class' => 'number-input'],
                ]);
            }

            $form
                ->add('info', TextType::class, [
                    'label' => 'unit.info',
                    'required' => false,
                    'attr' => ['class' => 'abc'],
                    'row_attr' => ['class' => 'mg-add-rows'],
                ])
                ->add('exercise', EntityType::class, [
                    'label' => 'exercise.exercise',
                    'class' => Exercise::class,
                    'choice_label' => 'name', // This is the property of exercise to display as option label
                    'attr' => ['class' => 'abc'],
                    'row_attr' => ['class' => 'mg-add-rows'],
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'unit.submit',
                    'attr' => ['class' => 'abc'],
                    'row_attr' => ['class' => 'mg-add-rows'],
                ])
            ;
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
            'translation_domain' => 'forms',
        ]);
    }
}
