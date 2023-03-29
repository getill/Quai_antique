<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository): Response
    {
        return $this->render('pages/reservation.html.twig', [
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'controller_name' => 'ReservationController',
        ]);
    }
}
