<?php

namespace App\Controller;

use App\Repository\RestaurantWeekdayRepository;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
  #[Route('/', name: 'home', methods: ['GET'])]
  public function index(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository): Response
  {

    $weekdays = $dayRepository->findAll();
    $time = $timeRepository->findAll();
    return $this->render('/pages/home.html.twig', [
      'time' => $time,
      'weekdays' => $weekdays
    ]);
  }
}
