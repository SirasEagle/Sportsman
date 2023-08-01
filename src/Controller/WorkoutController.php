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
     * @Route("/workout/transferehere", name="transfere_here_workout")
     */
    public function transfereToHere(Request $request)
    {
        $controllerDirectory = __DIR__;
        $fileContents = file_get_contents($controllerDirectory . '/../../../../_1 Java/alul/src/database/user0exer/exercises.txt');
        $dataSets = explode('--', $fileContents);

        $workoutData = [];

        foreach ($dataSets as $dataSet) {
            $lines = explode("\n", trim($dataSet));
            $date = new DateTime(array_shift($lines));

            $exercises = [];
            foreach ($lines as $line) {
                list($exercise, $values) = explode('-', $line, 2);
                $valuesArray = explode('-', $values);
                $exercises[$exercise] = array_map('intval', $valuesArray);
            }

            $workoutData[] = [
                'date' => $date,
                'exercises' => $exercises,
            ];
        }


        dd($workoutData);
        return new Response(json_encode($workoutData), 200, ['Content-Type' => 'application/json']);
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
