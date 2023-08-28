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
        $user0 = $userRepository->find(0);
        $user1 = $userRepository->find(1);

        // splitting the workouts into users
        $workoutsUser0 = [];
        $workoutsUser1 = [];
        foreach ($workouts as $workout) {
            if ($workout->getUser()->getId() === 0) {
                $workoutsUser0[] = $workout;
            } else {
                $workoutsUser1[] = $workout;
            }
        }

        return $this->render('workout/index.html.twig', [
            'workoutsUser0' => $workoutsUser0,
            'workoutsUser1' => $workoutsUser1,
            'user0' => $user0,
            'user1' => $user1,
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
            // Werte aus dem Formular holen und in das workout-Objekt schreiben
            $workout = $form->getData();

            // Holen Sie den ausgewählten Wert für 'user' aus dem Formular
            $selectedUserId = $form->get('user')->getData();
            $user = $this->entityManager->getRepository(User::class)->find($selectedUserId);
            $workout->setUser($user);

            // check if date already exists within other workouts
            $workoutRepository = $this->entityManager->getRepository(Workout::class);
            $workouts = $workoutRepository->findAll();
            $workoutsDates = array();

            // // uncomment, if there shoulkd only be one workout with taht date:
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
