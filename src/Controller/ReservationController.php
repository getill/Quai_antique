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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function reservation(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, Request $request, ReservationRepository $reservationrepository): Response
    {
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        // Get date
        $date = $request->get("date");
        // dd($date);

        // AJAX request verification
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('partials/_reservationButtons.html.twig', [
                    'date' => $date,
                    'time' => $timeRepository->findAll(),
                    'weekdays' => $dayRepository->findAll(),
                    'form' => $form->createView(),
                    'reservationTime' => $reservationrepository->findAll()
                ])
            ]);
        }

        return $this->render('pages/reservation.html.twig', [
            'date' => $date,
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'form' => $form->createView(),
            'reservationTime' => $reservationrepository->findAll()
        ]);
    }
}