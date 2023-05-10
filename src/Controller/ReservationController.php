<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function reservation(EntityManagerInterface $manager, RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, Request $request, ReservationRepository $reservationrepository): Response
    {
        //------ Get date
        $dateTime = $request->get("dateTime");
        $date = substr($dateTime, 0, -5);
        $selectedTime = substr($dateTime, -5);
        // dd($getTime);
        $dateTimeConverted = DateTime::createFromFormat("m/d/Y H:i", $dateTime);

        //------------------------- Form logic ------------------------

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class);
        // $selectedDate = new DateTime('now');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $reservation->setDateTime($dateTimeConverted);

            $this->addFlash(
                'success',
                'Votre réservation a bien été prise en compte!'
            );

            $manager->persist($reservation);
            $manager->flush();
        }

        //------------------------ Booked Time Array --------------------------------

        $bookedTime = $reservationrepository->findAll();

        foreach ($bookedTime as &$value) {
            $value = $value->getDateTime()->getTimestamp();
        }
        unset($value);

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
        $createdTimeAm = array();
        $createdTimePm = array();

        $openAm = $timeRepository->find($d)->getOpenAm()->getTimestamp();
        $closeAm = $timeRepository->find($d)->getCloseAm()->getTimestamp() - 3600; // Close Am - 1 hour

        $openPm = $timeRepository->find($d)->getOpenPm()->getTimestamp();
        $closePm = $timeRepository->find($d)->getClosePm()->getTimestamp() - 3600; // Close Am - 1 hour

        $fifteen_mins  = 15 * 60; // Time interval

        while ($openAm <= $closeAm) {
            if ($dateTime == null) {
                $createdTimeAm[] = date("H:i", $openAm); // AJAX + Open hours concateniation 
            } else {
                $dayDataAm = $date . ' ' . date("H:i", $openAm); // AJAX + Open hours concateniation 
                $createdTimeAm[] = DateTime::createFromFormat("m/d/Y H:i", $dayDataAm)->getTimestamp(); // Array storing + Timestamp conversion
            }
            $openAm += $fifteen_mins;
        }

        while ($openPm <= $closePm) {
            if ($dateTime == null) {
                $createdTimePm[] = date("H:i", $openPm); // AJAX + Open hours concateniation 
            } else {
                $dayDataPm = $date . ' ' . date("H:i", $openPm); // AJAX + Open hours concateniation 
                $createdTimePm[] = DateTime::createFromFormat("m/d/Y H:i", $dayDataPm)->getTimestamp(); // Array storing + Timestamp conversion
            }
            $openPm += $fifteen_mins;
        }

        //-------------- Compare [created time] and [booked times] -----------------------
        $resultAm = null;
        $resultPm = null;
        if ($timeRepository->find($d)->isIsClosed() == true) {
            $resultAm = "";
            $resultPm = "";
        } elseif ($dateTime == null) {
            $resultAm = "Choix";
        } else {
            $resultAm = array_diff($createdTimeAm, $bookedTime);
            $resultPm = array_diff($createdTimePm, $bookedTime);
        }
        unset($value);
        // dd($dateTime);

        //--------------- AJAX request verification -----------------------------

        if ($request->get('ajax')) {

            return new JsonResponse([
                'content' => $this->renderView('partials/_reservationButtons.html.twig', [
                    'date' => $date,
                    'selectedTime' => $selectedTime,
                    'time' => $timeRepository->findAll(),
                    'weekdays' => $dayRepository->findAll(),
                    'form' => $form->createView(),
                    'reservationDateTime' => $reservationrepository->findAll(),
                    'availableDateAm' => $resultAm,
                    'availableDatePm' => $resultPm,
                ])

            ]);
        }

        return $this->render('pages/reservation.html.twig', [
            'date' => $date,
            'time' => $timeRepository->findAll(),
            'selectedTime' => $selectedTime,
            'weekdays' => $dayRepository->findAll(),
            'form' => $form->createView(),
            'reservationDateTime' => $reservationrepository->findAll(),
            'availableDateAm' => $resultAm,
            'availableDatePm' => $resultPm,
        ]);
    }
}
