<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\Unit;
use App\Form\ExerciseEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/exercise", name="index_exercise")
     */
    public function index(): Response
    {
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercises = $exerciseRepository->findAll();

        // sorting the exercises
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
        ]);
    }

    /**
     * @Route("/exercise/add", name="new_exercise")
     */
    public function new(Request $request): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseEditType::class, $exercise);
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
     * @Route("/exercise/median", name="median_exercise")
     */
    public function median(Request $request)
    {
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercises = $exerciseRepository->findAll();

        foreach ($exercises as $exercise) {
            if (!$exercise) {
                throw $this->createNotFoundException('Exercise not found');
            }
            
            // Hier rufen wir die Units für die gegebene Exercise ab
            $unitRepository = $this->entityManager->getRepository(Unit::class);
            $units = $unitRepository->findBy(['exercise' => $exercise]);

            $median = 0;
            foreach ($units as $unit) {
                $median += $unit->getSet1();
                $median += $unit->getSet2();
                $median += $unit->getSet3();
            }
            $median = ($median / (count($units) * 3));
            $exercise->setMedian((float)$median);
            $this->entityManager->persist($exercise);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('index_exercise');
    }

    /**
     * @Route("/exercise/{id}", name="show_exercise")
     */
    public function show(int $id): Response
    {
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($id);

        if (!$exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }
        
        // Hier rufen wir die Units für die gegebene Exercise ab
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        $units = $unitRepository->findBy(['exercise' => $exercise]);
        
        // sorting the workouts
        usort($units, function ($a, $b) {
            $dateTimeA = $a->getWorkout()->getDate();
            $dateTimeB = $b->getWorkout()->getDate();
            if ($dateTimeA == $dateTimeB) {
                return 0;
            }
            return ($dateTimeA > $dateTimeB) ? -1 : 1;
        });

        return $this->render('exercise/show.html.twig', [
            'exercise' => $exercise,
            'units' => $units
        ]);
    }
}
