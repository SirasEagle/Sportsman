<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalendarController extends AbstractController
{
    private $entityManager;
    private $loaded = false;
    private $workouts = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function viewCalendar(Request $request): Response
    {
        $date = new DateTime('now');
        if (!$this->loaded) {
            $calendarRepository = $this->entityManager->getRepository(Workout::class);
            $this->workouts = $calendarRepository->findAll();
        }

        // Beispiel: Aktuellen Monat und Jahr ermitteln (ersetzen Sie dies durch Ihre eigene Logik):
        $currentDay = $date->format('d'); // Aktueller Monat
        $currentMonth = $date->format('m'); // Aktueller Monat
        $currentYear = $date->format('Y'); // Aktuelles Jahr
        $days = cal_days_in_month( 0, $currentMonth, $currentYear);

        return $this->render('calendar/view.html.twig', [
            'currentDay' => $currentDay,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'days' => $days,
            'workouts' => $this->workouts,
        ]);
    }

    #[Route('/calendar/{date}', name: 'app_calendar_date')]
    public function viewCalendarDate(Request $request, DateTime $date): Response
    {
        
        if (!$this->loaded) {
            $calendarRepository = $this->entityManager->getRepository(Workout::class);
            $this->workouts = $calendarRepository->findAll();
        }
        
        // Beispiel: Aktuellen Monat und Jahr ermitteln (ersetzen Sie dies durch Ihre eigene Logik):
        $currentDay = $date->format('d'); // Aktueller Monat
        $currentMonth = $date->format('m'); // Aktueller Monat
        $currentYear = $date->format('Y'); // Aktuelles Jahr
        $days = cal_days_in_month( 0, $currentMonth, $currentYear);

        return $this->render('calendar/view.html.twig', [
            'currentDay' => $currentDay,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'days' => $days,
            'workouts' => $this->workouts,
        ]);
    }
}
