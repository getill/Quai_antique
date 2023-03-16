<?php

namespace App\Controller;

use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
  #[Route('/', 'home.index', methods: ['GET'])]
  public function index(RestaurantWeekdayRepository $repository): Response
  {

    $weekdays = $repository->findAll();
    dd($weekdays);
    return $this->render('/pages/home.html.twig');
  }
}
