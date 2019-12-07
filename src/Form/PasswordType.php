<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as BasePasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => BasePasswordType::class,
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
            ;
    }
}
