<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantWeekdayRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RestaurantWeekdayTimetableRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function reservation(UserRepository $userRepository, EntityManagerInterface $manager, RestaurantWeekdayRepository $dayRepository, RestaurantWeekdayTimetableRepository $timeRepository, Request $request, ReservationRepository $reservationrepository, RestaurantRepository $restaurantRepository): Response
    {
        //-------------- AJAX request filtering ----------------

        $dateTime = $request->get("dateTime");
        $date = substr($dateTime, 0, -5);
        $selectedTime = substr($dateTime, -5);
        $dateTimeConverted = DateTime::createFromFormat("m/d/Y H:i", $dateTime);

        //------------------------- Form logic ------------------------

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class);
        $userData = $userRepository->findBy(['id' => $this->getUser()]);

        //------------------- Pre-fill user preference if connected ----------------

        if ($this->getUser()) {
            $pepolePref = $userData[0]->getPeoplePref();
            $form->get('nb_people')->setData($pepolePref);
        } else {
            $form->get('nb_people')->setData('1');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $reservation->setDateTime($dateTimeConverted);
            $reservation->setUser($this->getUser());

            $this->addFlash(
                'success',
                'Votre réservation a bien été prise en compte!'
            );
            $manager->persist($reservation);
            $manager->flush();
        }

        //------------------------ Max people logic --------------------------------

        $restaurantArray = $restaurantRepository->findAll();
        $maxPeople = $restaurantArray[0]->getMaxPeople();

        $bookedTime = $reservationrepository->findAll();

        $filtered_arrAM = array_filter(
            $bookedTime,
            function ($obj) use ($date) {
                $reservationDateTime = $obj->getDateTime(); // Take DateTime of every reservation
                $reservationTime = $reservationDateTime->format('H:i'); // convert to string with date format to "time only"
                if ($reservationTime <= "16:00") { // Takes every reservation below 16:00 (4pm)
                    $stringDate = $reservationDateTime->format('n/d/Y'); // convert to string with date format to "date only"
                    return $stringDate == $date; // Filter date based on selected date
                }
            }
        );

        $filtered_arrPM = array_filter(
            $bookedTime,
            function ($obj) use ($date) {
                $reservationDateTime = $obj->getDateTime();
                $reservationTime = $reservationDateTime->format('H:i');
                if ($reservationTime >= "16:00") { // Takes every reservation after 16:00 (4pm)
                    $stringDate = $reservationDateTime->format('n/d/Y');
                    return $stringDate == $date;
                }
            }
        );
        // dd($filtered_arrPM);

        foreach ($filtered_arrAM as &$value) {
            $value = $value->getNbPeople();
        } // Get nbPeople of every morning reservation
        foreach ($filtered_arrPM as &$value) {
            $value = $value->getNbPeople();
        } // Get nbPeople of every afternoon reservation

        $sumAM = array_sum($filtered_arrAM); // Sum of every nbPeople AM
        $sumPM = array_sum($filtered_arrPM); // Sum of every nbPeople PM

        unset($value);

        //------------------------ Booked Time Array --------------------------------

        foreach ($bookedTime as &$value) {
            $value = $value->getDateTime()->getTimestamp();
        }
        unset($value);

        //---------------------- Selected day condition ------------------------

        $intdate = strtotime($date); // AJAX date to timestamp 
        $d = 1; // Day ID on database
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

        //---------------------------- Creation of time based on selected day ---------------------------------

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

        //-------------- Display Logic -----------------------

        $resultAm = null;
        $resultPm = null;
        if ($timeRepository->find($d)->isIsClosed() == true) {
            $resultAm = "";
            $resultPm = "";
        } elseif ($dateTime == null) {
            $resultAm = "Choix";
        } elseif ($sumAM and $sumPM >= $maxPeople) {
            $resultAm = "FullAm";
            $resultPm = "FullPm";
        } elseif ($sumAM >= $maxPeople) {
            $resultAm = "FullAm";
            $resultPm = array_diff($createdTimePm, $bookedTime);
        } elseif ($sumPM >= $maxPeople) {
            $resultAm = array_diff($createdTimeAm, $bookedTime);
            $resultPm = "FullPm";
        } else { // Compare [created time] and [booked times]
            $resultAm = array_diff($createdTimeAm, $bookedTime);
            $resultPm = array_diff($createdTimePm, $bookedTime);
        }
        unset($value);

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
