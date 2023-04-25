<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/la_carte', name: 'menu', methods: ['GET'])]
    public function index(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, MenuRepository $menuRepoitory): Response
    {
        return $this->render('pages/menu.html.twig', [
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'plats' => $menuRepoitory->findAll(),
        ]);
    }
}
