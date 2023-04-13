<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Entity\RestaurantWeekdayTimetable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RestaurantWeekdayTimetableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function reservation(RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, Request $request, ReservationRepository $reservationrepository): Response
    {

        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        // Get date
        $date = $request->get("date");

        //------------- Time creation logic ------------------
        $array_of_time = array();
        $openAm = $timeRepository->find('1')->getOpenAm()->getTimestamp();
        $closeAm = $timeRepository->find('1')->getCloseAm()->getTimestamp();
        $fifteen_mins  = 15 * 60;

        while ($openAm <= $closeAm) {
            $array_of_time[] = date("H:i", $openAm);
            $openAm += $fifteen_mins;
        }
        //-------------------------------------------


        // AJAX request verification
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('partials/_reservationButtons.html.twig', [
                    'date' => $date,
                    'time' => $timeRepository->findAll(),
                    'weekdays' => $dayRepository->findAll(),
                    'form' => $form->createView(),
                    'reservationDateTime' => $reservationrepository->findAll(),
                    'reservationTime' => $array_of_time
                ])

            ]);
        }

        return $this->render('pages/reservation.html.twig', [
            'date' => $date,
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'form' => $form->createView(),
            'reservationDateTime' => $reservationrepository->findAll(),
            'reservationTime' => $array_of_time
        ]);
    }
}
