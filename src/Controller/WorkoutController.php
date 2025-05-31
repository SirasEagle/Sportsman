<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\Unit;
use App\Form\WorkoutNewType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class WorkoutController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/workout', name: 'index_workout')]
    public function index(): Response
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $workouts = $workoutRepository->findAll();

        // sorting the workouts
        usort($workouts, function ($a, $b) {
            $dateTimeA = $a->getDate();
            $dateTimeB = $b->getDate();
            if ($dateTimeA == $dateTimeB) {
                return 0;
            }
            return ($dateTimeA > $dateTimeB) ? -1 : 1;
        });

        // fetching the users
        $users = $userRepository->findAll();

        // initialise user-workout-arrays
        $workoutsUsers = [];
        foreach ($users as $user) {
            $workoutsUsers[$user->getId()] = [];
        }

        // assign workouts to users
        foreach ($workouts as $workout) {
            $userId = $workout->getUser()?->getId();
            if ($userId !== null && isset($workoutsUsers[$userId])) {
                $workoutsUsers[$userId][] = $workout;
            }
        }

        return $this->render('workout/index.html.twig', [
            'workoutsUsers' => $workoutsUsers,
            'users' => $users,
            'activeUser' => $activeUser->getId(),
        ]);
    }

    /**
     * @Route("/workout/add", name="new_workout")
     */
    public function new(Request $request): Response
    {
        $workout = new Workout();
        $form = $this->createForm(WorkoutNewType::class, $workout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workout = $form->getData();

            // initialise points
            $workout->setPoints(0);
            $workout->setUser($this->getUser());

            // check if date already exists within other workouts
            $workoutRepository = $this->entityManager->getRepository(Workout::class);
            $workouts = $workoutRepository->findAll();
            $workoutsDates = array();

            // // uncomment, if there should only be one workout with that date: TODO:
            // foreach ($workouts as $current) {
            //     // Die getName()-Werte dem neuen Array hinzufügen
            //     $workoutsDates[] = $current->getDate();
            // }
            // if (in_array($workout->getDate(), $workoutsDates)) {
            //     return new Response(json_encode(['message' => "Workout mit dem Datum " . $workout->getDate()->format('Y-m-d') . " gibt es schon."]), 200, ['Content-Type' => 'application/json']);
            // }

            // Das workout-Objekt in der Datenbank persistieren
            $this->entityManager->persist($workout);
            $this->entityManager->flush();

            // Umleiten zu einer anderen Route, z. B. zur Detailansicht des neuen workout
            return $this->redirectToRoute('show_workout', ['id' => $workout->getId()]);
        }

        return $this->render('workout/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/workout/points/{id}", name="points_workout")
     */
    public function points(Request $request, int $id)
    {
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workouts = $workoutRepository->findAll();

        foreach ($workouts as $workout) {
            if (!$workout) {
                throw $this->createNotFoundException('workout not found');
            }

            $points = 0;
            $units = $workout->getUnits();
            foreach ($units as $unit) {
                $temp = 0;

                $points++; // +1p for making an exercise
                if ($unit->getExercise()->getMultiplier()) {
                    // + fix additional points for making that exercise
                    $points += $unit->getExercise()->getMultiplier()->getAddition();
                }

                // +1p for each 50% median bases reps
                $temp += $unit->getSet1();
                $temp += $unit->getSet2();
                $temp += $unit->getSet3();
                $temp = (($temp / 3) * 2);
                if ($unit->getExercise()->getMultiplier() && ($unit->getExercise()->getMultiplier()->getMultiplyBy() != 0)) {
                    // multiply by set multiplier
                    $temp = $temp * $unit->getExercise()->getMultiplier()->getMultiplyBy();
                }
                $points += $temp / $unit->getExercise()->getMedian();
            }
            $workout->setPoints($points);
            $this->entityManager->persist($workout);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('show_workout', array('id' => $id));
    }

    /**
     * @Route("/workout/{id}", name="show_workout")
     */
    public function show(int $id): Response
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }
        
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workout = $workoutRepository->find($id);
        if ($activeUser->getId() !== $workout->getUser()?->getId()) {
            throw $this->createAccessDeniedException('You can only view your own workouts.');
        }
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercises = $exerciseRepository->findAll();

        if (!$workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        // sorting the exercises
        usort($exercises, function ($a, $b) {
            $nameA = $a->getName();
            $nameB = $b->getName();
            if ($nameA == $nameB) {
                return 0;
            }
            return ($nameA < $nameB) ? -1 : 1;
        });

        return $this->render('workout/show.html.twig', [
            'workout' => $workout,
            'exercises' => $exercises,
        ]);
    }
}
