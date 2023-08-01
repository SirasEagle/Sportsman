<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use App\Entity\Exercise;
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
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workouts = $workoutRepository->findAll();

        return $this->render('workout/index.html.twig', [
            'workouts' => $workouts,
        ]);
    }

    /**
     * @Route("/workout/transfere/here/workouts", name="transfere_here_workout")
     */
    public function transfereToHereWorkouts(Request $request)
    {
        $change = 0;

        // Load Workouts
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workouts = $workoutRepository->findAll();
        $workoutsDates = array();
        foreach ($workouts as $workout) {
            // Die getName()-Werte dem neuen Array hinzufügen
            $workoutsDates[] = $workout->getDate();
        }

        $controllerDirectory = __DIR__;
        $fileContents = file_get_contents($controllerDirectory . '/../../../../_1 Java/alul/src/database/user0exer/exercises.txt');
        $dataSets = explode('--', $fileContents);

        foreach ($dataSets as $dataSet) {
            $lines = explode("\n", trim($dataSet));
            $date = new DateTime(array_shift($lines));

            // Extract and persist workout
            if (in_array($date, $workoutsDates)) {
                error_log("Workout mit dem Datum " . $date->format('Y-m-d') . " gibt es schon.");
                return new Response(json_encode(['message' => 'Keine Daten verarbeitet']), 200, ['Content-Type' => 'application/json']);
            }
            $change = 1;
            $workout = new Workout();
            $workout->setDate($date);
            $workout->setIsReal(true);
            $this->entityManager->persist($workout);
            $this->entityManager->flush();
        }

        if ($change == 1) {
            return new Response(json_encode(['message' => 'Es wurden Daten verarbeitet']), 200, ['Content-Type' => 'application/json']);
        }
        return new Response(json_encode(['message' => 'Keine Daten verarbeitet']), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/workout/transfere/here/exercises", name="transfere_here_workout")
     */
    public function transfereToHereExercises(Request $request)
    {
        $change = 0;
        $controllerDirectory = __DIR__;
        $fileContents = file_get_contents($controllerDirectory . '/../../../../_1 Java/alul/src/database/user0exer/exercises.txt');
        $dataSets = explode('--', $fileContents);

        foreach ($dataSets as $dataSet) {
            $lines = explode("\n", trim($dataSet));
            $date = new DateTime(array_shift($lines)); // important, dont remove

            foreach ($lines as $line) {
                // Load exercises
                $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
                $exercises = $exerciseRepository->findAll();
                $exercisesNames = array();
                foreach ($exercises as $exercise) {
                    // Die getName()-Werte dem neuen Array hinzufügen
                    $exercisesNames[] = $exercise->getName();
                }

                list($txtExercise, $values) = explode('-', $line, 2);
                if (!in_array($txtExercise, $exercisesNames)) {
                    $change = 1;
                    $exercise = new Exercise();
                    $exercise->setName($txtExercise);
                    $this->entityManager->persist($exercise);
                    $this->entityManager->flush();
                }
            }
        }

        if ($change == 1) {
            return new Response(json_encode(['message' => 'Es wurden Daten verarbeitet']), 200, ['Content-Type' => 'application/json']);
        }
        return new Response(json_encode(['message' => 'Keine Daten verarbeitet']), 200, ['Content-Type' => 'application/json']);
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
            // Werte aus dem Formular holen und in das workout-Objekt schreiben
            $workout = $form->getData();

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
     * @Route("/workout/{id}", name="show_workout")
     */
    public function show(int $id): Response
    {
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workout = $workoutRepository->find($id);
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercises = $exerciseRepository->findAll();

        if (!$workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        return $this->render('workout/show.html.twig', [
            'workout' => $workout,
            'exercises' => $exercises,
        ]);
    }
}
