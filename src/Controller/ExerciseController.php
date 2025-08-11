<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\Multiplier;
use App\Entity\MuscleGroup;
use App\Entity\Unit;
use App\Entity\Workout;
use App\Form\ExerciseNewType;
use App\Form\ExerciseEditType1;
use App\Service\StatisticsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    private $entityManager;
    private $statisticsService;

    public function __construct(EntityManagerInterface $entityManager, StatisticsService $statisticsService)
    {
        $this->entityManager = $entityManager;
        $this->statisticsService = $statisticsService;
    }

    /**
     * @Route("/exercise", name="index_exercise")
     */
    public function index(): Response
    {
        // Fetch all exercises
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercises = $exerciseRepository->findAll();

        $categories = [];

        // Fetch all muscleGroups
        $muscleGroupRepository = $this->entityManager->getRepository(MuscleGroup::class);
        $muscleGroups = $muscleGroupRepository->findAll();

        // Initialize categories array with empty arrays for each muscle group
        foreach ($muscleGroups as $muscleGroup) {
            $categories[$muscleGroup->getTerm()] = [];
        }

        // Populate the categories array with exercises
        foreach ($exercises as $exercise) {
            $muscleGroup = $exercise->getMuscleGroup();
            $muscleGroupName = $muscleGroup->getTerm();
            $categories[$muscleGroupName][] = $exercise;
        }

        // Remove empty categories
        foreach ($categories as $muscleGroupName => $thisExercises) {
            if (empty($thisExercises)) {
                unset($categories[$muscleGroupName]);
            }
        }

        // Sort the muscle groups alphabetically
        ksort($categories);

        // Sort the exercises alphabetically for each muscle group
        foreach ($categories as &$thisExercises) {
            // sorting the exercises
            usort($thisExercises, function ($a, $b) {
                $nameA = $a->getName();
                $nameB = $b->getName();
                if ($nameA == $nameB) {
                    return 0;
                }
                return ($nameA < $nameB) ? -1 : 1;
            });
        }

        // Sort the exercises alphabetically for overall view
        usort($exercises, function ($a, $b) {
            $nameA = $a->getName();
            $nameB = $b->getName();
            if ($nameA == $nameB) {
                return 0;
            }
            return ($nameA < $nameB) ? -1 : 1;
        });


        return $this->render('exercise/index.html.twig', [
            'exercises' => $exercises,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/exercise/add", name="new_exercise")
     */
    public function new(Request $request): Response
    {
        $exercise = new Exercise();
        $multiplier = new Multiplier();
        $multiplier->setExercise($exercise);
        $exercise->setMultiplier($multiplier);

        $form = $this->createForm(ExerciseNewType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Werte aus dem Formular holen und in das Exercise-Objekt schreiben
            $exercise = $form->getData();

            // Das Exercise-Objekt in der Datenbank persistieren
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            // Umleiten zu einer anderen Route, z. B. zur Detailansicht des neuen Exercises
            return $this->redirectToRoute('show_exercise', ['id' => $exercise->getId()]);
        }

        return $this->render('exercise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/exercise/edit/{id}", name="edit_exercise")
     */
    public function edit(Request $request, int $id): Response
    {
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($id);

        $multiplier = $exercise->getMultiplier();
        if (!$multiplier) {
            $multiplier = new Multiplier();
            $multiplier->setExercise($exercise);
            $exercise->setMultiplier($multiplier);
        }

        $form = $this->createForm(ExerciseEditType1::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Werte aus dem Formular holen und in das Exercise-Objekt schreiben
            $exercise = $form->getData();

            // Das Exercise-Objekt in der Datenbank persistieren
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();

            // Umleiten zu einer anderen Route, z. B. zur Detailansicht des neuen Exercises
            return $this->redirectToRoute('show_exercise', ['id' => $exercise->getId()]);
        }

        return $this->render('exercise/edit.html.twig', [
            'form' => $form->createView(),
            'exercise' => $exercise,
        ]);
    }

    #[Route("/exercise/median/{id}", name: "median_exercise")]
    public function median(Request $request, int $id)
    {
        $this->statisticsService->calculateMedian();

        return $this->redirectToRoute('show_workout', array('id' => $id));
    }

    /**
     * @Route("/exercise/{id}", name="show_exercise")
     */
    public function show(int $id): Response
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($id);

        // get today's workout for the user if it exists
        $today = new \DateTime();
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $latest_workout = null;
        $latest_workout = $workoutRepository->findOneBy([
            'user' => $activeUser,
            'date' => $today, // today's date
        ]);
        if (!$latest_workout) {
            $latest_workout = null;
        }

        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workouts = $workoutRepository->findBy(['user' => $activeUser]);
        
        // Hier rufen wir die Units für die gegebene Exercise ab
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        $units = $unitRepository->findBy(['exercise' => $exercise, 'workout' => $workouts]);
        
        // sorting the workouts from oldest to newest
        usort($units, function ($a, $b) {
            $dateA = $a->getWorkout()->getDate();
            $dateB = $b->getWorkout()->getDate();
            // support older versions of PHP for comparison
            if ($dateA == $dateB) {
                return 0;
            }
            return ($dateA < $dateB) ? -1 : 1;
            
        });

        return $this->render('exercise/show.html.twig', [
            'exercise' => $exercise,
            'units' => $units,
            'latest_workout' => $latest_workout,
        ]);
    }
}
