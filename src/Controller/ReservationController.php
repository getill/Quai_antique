<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function reservation(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, Request $request, ReservationRepository $repository): Response
    {
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        return $this->render('pages/reservation.html.twig', [
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'form' => $form->createView(),
            'reservationTime' => $repository->findAll()
        ]);
    }
}
