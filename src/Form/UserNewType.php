<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email:',
                'attr' => ['class' => 'abc'],
            ])
            ->add('password', TextType::class, [
                'label' => 'Password:',
                'attr' => ['class' => 'abc'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name:',
                'attr' => ['class' => 'abc'],
            ])
            ->add('level', TextType::class, [
                'label' => 'Level:',
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
            'data_class' => User::class,
        ]);
    }
}
