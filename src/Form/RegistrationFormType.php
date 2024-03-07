<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'name',                     
                    'class' => 'form-control',
                    'placeholder' => 'Name'
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => "Gender",
                'choice_attr' => [
                    'autocomplete' => 'gender',                     
                    'class' => 'form-control mt-1',
                    'placeholder' => 'Gender'
                ],
                'attr' => ['class' => 'form-control mt-1'],
                'choices' => [
                    'Male' => 0,
                    'Female' => 1
                ]
            ])
            ->add('birthdate', DateType::class, [
                'label' => "Birthdate",
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                // 'input'  => 'datetime_immutable',
                'attr' => ['class' => 'datepicker form-control mt-1'],
            ])
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'email',                     
                    'class' => 'form-control mt-2',
                    'placeholder' => 'Email'
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => ['class' => 'mt-2'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',                     
                    'class' => 'form-control mt-2',
                    'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your passwor0d should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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