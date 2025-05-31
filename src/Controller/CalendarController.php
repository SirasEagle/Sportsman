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
                $this->workouts = $calendarRepository->findAll(); //TODO: not all, just of the logged in user
                $this->loaded = true;
            } catch (\Throwable $th) {
                printf("[e250419-001] %s\n", $th->getMessage());
            }
        }
    }

    private function getCurrentMonthData(DateTime $date)
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $currentMonth = $date->format('m');
        $currentYear = $date->format('Y');
        $days = cal_days_in_month(0, $currentMonth, $currentYear);

        $tempdates = $this->workouts;
        $this->workouts = [];
        foreach ($tempdates as $workout) {
            $date = $workout->getDate();
            if (($workout->getUser()->getId() == $activeUser->getId()) && $date && $date->format('m') == $currentMonth && $date->format('Y') == $currentYear) {
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
            'activeUser' => $activeUser->getId(),
            'monthPoints' => $monthPoints,
            'users' => $users,
        ];
    }

    /**In summary:
This method updates the user's "last trained" date for each muscle group, setting it to the most recent workout date where that muscle group was exercised. */
    private function bubi(DateTime $date)
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $muscleGroupRepository = $this->entityManager->getRepository(MuscleGroup::class);
        $muscleGroups = $muscleGroupRepository->findAll();
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($activeUser->getId());

        foreach ($this->workouts as $workout) {
            foreach ($workout->getUnits() as $unit) {
                foreach ($user->getLastMuscleGroups() as $currentGroup) {
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
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $this->loadWorkoutsIfNotLoaded();
        $date = new DateTime('now');
        $data = $this->getCurrentMonthData($date);

        return $this->render('calendar/view.html.twig', $data);
    }

    #[Route('/calendar/{date}/{user}', name: 'app_calendar_date')]
    public function viewCalendarDate(Request $request, DateTime $date, int $user): Response
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $this->loadWorkoutsIfNotLoaded();
        $data = $this->getCurrentMonthData($date);

        return $this->render('calendar/view.html.twig', $data);
    }
}
