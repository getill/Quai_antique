<?php

namespace App\Controller;

use App\Form\ReservationType;
use App\Repository\ReservationRepository;
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
        $createdTime = array();
        $openAm = $timeRepository->find('1')->getOpenAm()->getTimestamp();
        $closeAm = $timeRepository->find('1')->getCloseAm()->getTimestamp();
        $fifteen_mins  = 15 * 60;


        //-------------- transorm every booked datetime into timestamp ------------
        $bookedTime = $reservationrepository->findAll();

        foreach ($bookedTime as &$value) {
            $value = $value->getDateTime()->getTimestamp();
            $value = date("H:i", $value);
        }
        // dd($bookedTime);
        unset($value);

        while ($openAm <= $closeAm) {
            $createdTime[] = date("H:i", $openAm);
            $openAm += $fifteen_mins;
        }

        $result = array_diff($createdTime, $bookedTime);
        print_r($result);
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
                    'availableDate' => $result
                ])

            ]);
        }

        return $this->render('pages/reservation.html.twig', [
            'date' => $date,
            'time' => $timeRepository->findAll(),
            'weekdays' => $dayRepository->findAll(),
            'form' => $form->createView(),
            'reservationDateTime' => $reservationrepository->findAll(),
            'availableDate' => $result
        ]);
    }
}
