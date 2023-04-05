<?php

namespace App\Form;

use App\Form\Type\DateTimePickerType;
use App\Entity\Horaires;
use App\Entity\Reservation;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{

    // public function buildView(FormView $view, FormInterface $form, $options): void
    // {
    //     $view->vars['available_times'] = $this->getAvailableTimes($options['date']);
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_time', DateTimeType::class, [
                // 'class' => Reservation::class,
                // 'html5' => false,
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

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     $date_time = $data->;
        //     $disponibility = null === $date_time ? [] : $date_time;

        //     $form->add('Horaires disponibles:', EntityType::class, [
        //         'class' => Horaires::class,
        //         'placeholder' => '',
        //         'choices' => $disponibility,
        //     ]);
        // });

    }

    // private function getAvailableTimes($date)
    // {
    //     // Récupérer les horaires disponibles pour la date spécifiée
    //     // et retourner un tableau d'options pour le champ de formulaire
    //     $availableTimes = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
    //     return array_combine($availableTimes, $availableTimes);
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            // $resolver->setDefined('date')
        ]);
    }
}