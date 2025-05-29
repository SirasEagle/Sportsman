<?php

namespace App\Form;

use App\Config\Category;
use App\Entity\Company;
use App\Entity\Portion;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'product.name',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('category', EnumType::class, [
                'label' => 'category.category',
                'class' => Category::class,
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('packageSize', TextType::class, [
                'label' => 'product.package_size',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'number-input'],
            ])
            ->add('company', EntityType::class, [
                'label' => 'Company',
                'class' => Company::class,
                'choice_label' => 'name', // This is the property of company to display as option label
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'product.submit',
                'attr' => ['class' => 'abc'],
                'row_attr' => ['class' => 'mg-add-rows'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'translation_domain' => 'forms',
        ]);
    }
}
