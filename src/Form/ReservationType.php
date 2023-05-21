<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nb_people', IntegerType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-5',
                ],
                'attr' => [
                    'placeholder' => 'Nombre de convives',
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 13
                ],
                'label' => 'Nombre de convives',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('firstname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ],
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control',
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('secondname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ],
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('email', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating my-2',
                ],
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'row_attr' => [
                    'class' => 'my-auto',
                ],
                'attr' => [
                    'class' => 'btn btn-danger lexend'
                ],
                'label' => 'Réserver'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
