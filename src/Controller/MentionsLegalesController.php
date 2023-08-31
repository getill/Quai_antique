<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions_legales', name: 'mentions')]
    public function index(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository): Response
    {
        return $this->render('pages/mentions.html.twig', [
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
        ]);
    }
}
