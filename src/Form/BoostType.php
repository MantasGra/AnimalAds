<?php

namespace App\Form;

use App\Entity\Boost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class BoostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('tomorrow'),
                        'message' => 'Please select a future date.'
                    ])
                ]
            ])
            ->add('duration', ChoiceType::class, [
                'placeholder' => 'Select duration',
                'choices' => ['1 day' => 1, '3 days' => 3, '1 week' => 7, '1 month' => 30],
            ])
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Select type',
                'choices' => ['Premium' => 'Premium', 'Basic' => 'Basic'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Boost::class,
        ]);
    }
}
