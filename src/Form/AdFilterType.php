<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filterFrom', DateType::class, [
                'required'   => false,
                'widget' => 'single_text',
            ])
            ->add('filterTo', DateType::class, [
                'required'   => false,
                'widget' => 'single_text',
            ])
            ->add('filterCategory', EntityType::class, [
                'row_attr' => ['class' => 'ui dropdown'],
                'class' => Category::class,
                'required'   => false,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
            ])
            ->add('priceFrom', NumberType::class, [
                'required'   => false,
                'attr' => [
                    'min'  => 0,
                    'step' => 0.01,
                ]
            ])
            ->add('priceTo', NumberType::class, [
                'required'   => false,
                'attr' => [
                    'min'  => 0,
                    'step' => 0.01,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
