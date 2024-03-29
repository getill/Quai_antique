<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
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
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ -]*$/u',
                        'message' => 'Veuillez entrer un prénom valide'
                    ]),
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
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ -]*$/u',
                        'message' => 'Veuillez entrer un nom valide'
                    ]),
                ]
            ])

            ->add('plainPassword', PasswordType::class, [
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
            ])
            ->add('submit', SubmitType::class, [
                'row_attr' => [
                    'class' => 'd-grid gap-2 col-xl-6 mx-auto',
                ],
                'attr' => [
                    'class' => 'btn btn-danger my-5'
                ],
                'label' => 'Modifier'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
