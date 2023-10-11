<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CalendarController extends AbstractController
{
    private $entityManager;
    private $loaded = false;
    private $workouts = [];
    private $userId = 0;

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

        $tempdates = $this->workouts;
        $this->workouts = [];
        foreach ($tempdates as $workout) {
            $date = $workout->getDate();
            if (($workout->getUser()->getId() == $this->userId) && $date && $date->format('m') == $currentMonth && $date->format('Y') == $currentYear) {
                $this->workouts[] = $workout;
            }
        }

        // fetching the users
        $userRepository = $this->entityManager->getRepository(User::class);
        $user0 = $userRepository->find(0);
        $user1 = $userRepository->find(1);

        return [
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'days' => $days,
            'workouts' => $this->workouts,
            'activeUser' => $this->userId,
            'user0' => $user0,
            'user1' => $user1,
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

    #[Route('/calendar/{date}/{user}', name: 'app_calendar_date')]
    public function viewCalendarDate(Request $request, DateTime $date, int $user): Response
    {
        $this->loadWorkoutsIfNotLoaded();
        $this->userId = $user;
        $data = $this->getCurrentMonthData($date);

        return $this->render('calendar/view.html.twig', $data);
    }
}
