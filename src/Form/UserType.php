<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First name'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last name'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Gender' => '',
                    'Male' => 'male',
                    'Female' => 'female',
                    'Non-binary' => null
                ],
                'data' => '',
                'attr' => [
                    'class' => 'ui dropdown'
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'placeholder' => 'Phone number'
                ]
            ])
            ->add('country', TextType::class, [
                'attr' => [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'City'
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Address'
                ]
            ])
            ->add('birthDate', DateType::class, [
                'years' => range(date('1920'), date('Y')),
                'attr' => [
                    'placeholder' => 'Birth Date'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
