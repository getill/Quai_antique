<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CGUController extends AbstractController
{
    #[Route('/cgu', name: 'cgu')]
    public function index(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository): Response
    {
        return $this->render('pages/cgu.html.twig', [
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
        ]);
    }
}
