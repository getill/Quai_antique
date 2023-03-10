<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating my-5',
                ],
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])

            ->add('second_name', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mt-5',
                ],
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])

            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'form-floating mt-5',
                ],
                'attr' => [
                    'placeholder' => 'Adresse email',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'row_attr' => [
                        'class' => 'form-floating mt-5',
                    ],
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                        'class' => 'form-control',
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ]
                ],
                'second_options' => [
                    'row_attr' => [
                        'class' => 'form-floating mt-5',
                    ],
                    'attr' => [
                        'placeholder' => 'Confirmation du mot de passe',
                        'class' => 'form-control',
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])

            ->add('people_pref', IntegerType::class, [
                'row_attr' => [
                    'class' => 'form-floating mt-5',
                ],
                'attr' => [
                    'placeholder' => 'Nombre de convives préférés',
                    'class' => 'form-control',
                ],
                'label' => 'Nombre de convives préférés',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['max' => '9'])
                ],
                'invalid_message' => '9 convives maximum.'
            ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger my-5'
                ],
                'label' => 'S\'inscrire'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}