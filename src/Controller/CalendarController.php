<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CalendarController extends AbstractController
{
    private $entityManager;
    private $loaded = false;
    private $workouts = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function loadWorkoutsIfNotLoaded()
    {
        if (!$this->loaded) {
            $calendarRepository = $this->entityManager->getRepository(Workout::class);
            $this->workouts = $calendarRepository->findAll();
            $this->loaded = true;
        }
    }

    private function getCurrentMonthData(DateTime $date)
    {
        $currentMonth = $date->format('m');
        $currentYear = $date->format('Y');
        $days = cal_days_in_month(0, $currentMonth, $currentYear);

        return [
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'days' => $days,
            'workouts' => $this->workouts,
        ];
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function viewCalendar(Request $request): Response
    {
        $this->loadWorkoutsIfNotLoaded();
        $date = new DateTime('now');
        $data = $this->getCurrentMonthData($date);

        return $this->render('calendar/view.html.twig', $data);
    }

    #[Route('/calendar/{date}', name: 'app_calendar_date')]
    public function viewCalendarDate(Request $request, DateTime $date): Response
    {
        $this->loadWorkoutsIfNotLoaded();
        $data = $this->getCurrentMonthData($date);

        return $this->render('calendar/view.html.twig', $data);
    }
}
