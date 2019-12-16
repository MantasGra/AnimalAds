<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', ChoiceType::class, [
                'row_attr' => ['class' => 'ui dropdown'],
                'choices'  => [
                    'Dog' => 'dog',
                    'Cat' => 'cat',
                    'Mouse' => 'mouse',
                    'Wolf' => 'wolf',
                    'Monkey' => 'monkey',
                    'Other' => 'other',
                ],
            ])
            ->add('breed')
            ->add('weight', NumberType::class, [
                'attr' => [
                    'min'  => 0,
                    'step' => 0.5,
                ]
            ])
            ->add('volume', NumberType::class, [
                'attr' => [
                    'min'  => 0,
                    'step' => 0.5,
                ]
            ])
            ->add('height', NumberType::class, [
                'attr' => [
                    'min'  => 0,
                    'step' => 0.5,
                ]
            ])
            ->add('bornAt', DateType::class, [
                'widget' => 'single_text',
         
             ])
            ->add('addictions')
            ->add('trained', CheckboxType::class, [
                'label'  => 'Is animal trained?',
                'required' => false,
            ])
            ->add('vaccinated', CheckboxType::class, [
                'label'  => 'Is animal vaccinated?',
                'required' => false,
            ])
            ->add('safeToTransport', CheckboxType::class, [
                'label'  => 'Is animal safe to transport?',
                'required' => false,
            ])
            ->add('friendly', CheckboxType::class, [
                'label'  => 'Is animal friendly?',
                'required' => false,
            ])
            ->add('color');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
