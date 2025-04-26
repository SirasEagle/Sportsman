<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
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
    private $userId = 1; // TODO: warum? nur weil Adrian ist bis jetzt

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads all workout entries into the private array $workouts
     */
    private function loadWorkoutsIfNotLoaded()
    {
        if (!$this->loaded) {
            try {
                $calendarRepository = $this->entityManager->getRepository(Workout::class);
                $this->workouts = $calendarRepository->findAll();
                $this->loaded = true;
            } catch (\Throwable $th) {
                printf("[e250419-001] %s\n", $th->getMessage());
            }
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

        $monthPoints = 0;
        foreach ($this->workouts as $workout) {
            $monthPoints += $workout->getPoints();
        }

        // fetching the users
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $bree = $this->bubi($date);

        return [
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'days' => $days,
            'workouts' => $this->workouts,
            'activeUser' => $this->userId,
            'monthPoints' => $monthPoints,
            'users' => $users,
        ];
    }

    private function bubi(DateTime $date)
    {
        $muscleGroupRepository = $this->entityManager->getRepository(MuscleGroup::class);
        $muscleGroups = $muscleGroupRepository->findAll();
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($this->userId);

        foreach ($this->workouts as $workout) {
            foreach ($workout->getUnits() as $unit) {
                foreach ($user->getLastMuscleGroups() as $currentGroup) {
                    // var_dump($currentGroup->getMuscleGroup()->getTerm());
                    if ($currentGroup->getMuscleGroup()->getId() == $unit->getExercise()->getMuscleGroup()->getId()) {
                        if ($currentGroup->getDate() < $workout->getDate()) {
                            $currentGroup->setDate($workout->getDate());
                            $this->entityManager->persist($currentGroup);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }
        // exit;
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
