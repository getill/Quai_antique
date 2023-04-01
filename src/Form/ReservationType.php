<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_time', DateType::class, [
                'html5' => false,
                'row_attr' => [
                    'class' => 'my-5',
                ],
                'placeholder' => [
                    'month' => 'Mois', 'day' => 'Jour',
                ],
                'attr' => [
                    'class' => 'form-label'
                ],
                'label' => 'Date et heure de réservation'
            ])
            ->add('nb_people', IntegerType::class, [
                'row_attr' => [
                    'class' => 'form-floating my-5',
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
            ->add('submit', SubmitType::class, [
                'row_attr' => [
                    'class' => 'd-grid gap-2 col-xl-6 mx-auto',
                ],
                'attr' => [
                    'class' => 'btn btn-danger my-5'
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
