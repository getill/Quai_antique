<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder

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

      ->add('newPassword', PasswordType::class, [
        'row_attr' => [
          'class' => 'form-floating mt-5',
        ],
        'attr' => [
          'placeholder' => 'Nouveau mot de passe',
          'class' => 'form-control'
        ],
        'label' => 'Nouveau mot de passe',
        'label_attr' => ['class' => 'form-label'],
        'constraints' => [new Assert\NotBlank()]
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
}
