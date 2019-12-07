<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'New password',
                    'attr' => [
                        'placeholder' => 'Password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat new password',
                    'attr' => [
                        'placeholder' => 'Repeat Password'
                    ]
                ],

            ])
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
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
