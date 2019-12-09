<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
              
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'min'  => 0,
                    'step' => 0.01,
                ]
            ])
            ->add('description', TextareaType::class, [])
            ->add('type', ChoiceType::class, [
                'row_attr' => ['class' => 'ui dropdown'],
                'choices'  => [
                    'For rent' => true,
                    'For sale' => false,
                ],              
            ])
            ->add('hidden', CheckboxType::class, [
                'label'  => 'Is Ad hidden?',
                'required' => false,

            ])
            ->add('animal', EntityType::class, [
                'row_attr' => ['class' => 'ui dropdown'],
                'class' => Animal::class,   
                'choice_label' => function ($animal) {
                    return $animal->getName();
                }  
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
