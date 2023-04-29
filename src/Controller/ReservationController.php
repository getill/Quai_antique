<?php

namespace App\Controller;

use DateTime;
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

        //------ Get date
        $date = $request->get("date");

        //------------------------ Booked Time Array --------------------------------

        $bookedTime = $reservationrepository->findAll();

        foreach ($bookedTime as &$value) {
            $value = $value->getDateTime()->getTimestamp();
        }
        unset($value);

        $result = null;

        //------------- Time creation logic ------------------

        //-------- Selected day condition
        $d = 1; // Day ID on database
        $intdate = strtotime($date); // AJAX date to timestamp 
        $dayOfWeek = date("D", $intdate); // Get the day "mon, tue, wen...."

        if ($dayOfWeek == "Mon") {
            $d = 1;
        } elseif ($dayOfWeek == "Tue") {
            $d = 2;
        } elseif ($dayOfWeek == "Wed") {
            $d = 3;
        } elseif ($dayOfWeek == "Thu") {
            $d = 4;
        } elseif ($dayOfWeek == "Fri") {
            $d = 5;
        } elseif ($dayOfWeek == "Sat") {
            $d = 6;
        } elseif ($dayOfWeek == "Sun") {
            $d = 7;
        }

        //----------------- Creation of time based on selected day
        $createdTime = array();
        $openAm = $timeRepository->find($d)->getOpenAm()->getTimestamp();
        $closeAm = $timeRepository->find($d)->getCloseAm()->getTimestamp() - 3600; // Close Am - 1 hour
        $fifteen_mins  = 15 * 60; // Time interval

        while ($openAm <= $closeAm) {
            if ($date == null) {
                $createdTime[] = date("H:i", $openAm); // AJAX + Open hours concateniation 
            } else {
                $dayData = $date . ' ' . date("H:i", $openAm); // AJAX + Open hours concateniation 
                $createdTime[] = DateTime::createFromFormat("d/m/Y H:i", $dayData)->getTimestamp(); // Array storing + Timestamp conversion
            }
            $openAm += $fifteen_mins;
        }

        //-------------- Compare [created time] and [booked times] -----------------------

        if ($date == null) {
            $result = $createdTime;
        } else {
            $result = array_diff($createdTime, $bookedTime);
        }
        unset($value);

        //--------------- AJAX request verification -----------------------------

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

        // dd($result);

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
