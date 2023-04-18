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
        // dd($date);

        //------------- Time creation logic ------------------

        $createdTime = array();
        $openAm = $timeRepository->find('1')->getOpenAm()->getTimestamp();
        $closeAm = $timeRepository->find('1')->getCloseAm()->getTimestamp() - 3600; // Close Am - 1 hour
        // dd($closeAm);
        $fifteen_mins  = 15 * 60;

        while ($openAm <= $closeAm) {
            $createdTime[] = date("H:i", $openAm);
            $openAm += $fifteen_mins;
        }

        //-------------- transorm every booked datetime into timestamp ------------


        //---- Booked Time Array 

        $bookedTime = $reservationrepository->findAll();

        foreach ($bookedTime as &$value) {
            $value = $value->getDateTime()->getTimestamp();
            $value = date("H:i", $value);
        }
        unset($value);
        // dd($bookedTime);




        //-------------- Compare [created time] and [booked times] -----------------------

        // print_r($result);



        //--------------- AJAX request verification -----------------------------

        $bookedDate = $reservationrepository->findAll();
        $result = null;
        // dd($value, $date);
        if ($request->get('ajax')) {

            //----- Booked Date Array

            foreach ($bookedDate as &$value) {
                $value = $value->getDateTime()->getTimestamp();
                $value = date("d/m/Y", $value);
                $result = array_search($date, $bookedDate);
            }
            // dd($bookedDate);
            dd($result);
            // dd($bookedDate, $date);
            unset($value);
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

        $result = array_diff($createdTime, $bookedTime);

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
