<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UnitEditType;
use App\Form\UnitNewType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Unit;
use App\Entity\Exercise;
use App\Entity\Workout;

class UnitController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/unit', name: 'app_unit')]
    public function index(): Response
    {
        return $this->render('unit/index.html.twig', [
            'controller_name' => 'UnitController',
        ]);
    }

    #[Route('/unit/add/{id}', name: 'add_unit')]
    public function new(Request $request, int $id): Response
    {
        // Ensure the user is logged in
        $activeUser = $this->getUser();
        if (!$activeUser) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $unit = new Unit();

        $today = new \DateTime();
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $currentWorkout = $workoutRepository->findOneBy([
            'date' => $today,
            'user' => $activeUser, // Assuming the user is the currently logged-in user
            // TODO: check user as well
        ]);
        // case: no workout for today exists
        if (!$currentWorkout) {
            return $this->render('unit/confirm_new_workout.html.twig', [
                'exerciseId' => $id,
                'todayDate'  => $today->format('Y-m-d'),
            ]);
        }
        $unit->setWorkout($currentWorkout);

        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($id);
        $unit->setExercise($exercise);

        $form = $this->createForm(UnitNewType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit = $form->getData();

            if ($unit->getExercise()->getUsesWeight()) {
                $weightString = $form->get('weight')->getData();
                $weight = $this->weightStringToFloat($weightString);
                $unit->setWeight($weight);
            }

            // set set1, set2, set3 to 1 if isSingleUnit is true
            if ($unit->getExercise()->isSingleUnit()) {
                $unit->setSet1(1);
                $unit->setSet2(1);
                $unit->setSet3(1);
            }

            $this->entityManager->persist($unit);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_workout', ['id' => $unit->getWorkout()->getId()]);
        }

        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Handles the addition of a new unit via an XHR request.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/unit/add-xhr', name: 'add_unit_xhr', methods: ['POST'])]
    public function addUnitXhr(Request $request): Response
    {
        // Ensure the user is logged in
        /** @var \App\Entity\User $activeUser */ // prevents IDE warnings
        $activeUser = $this->getUser();
        if (!$activeUser) {
            return new Response('You must be logged in to add a unit.', Response::HTTP_UNAUTHORIZED);
        }
        
        // Validate the request parameters
        if (!$request->request->has('set1') || !$request->request->has('set2') || !$request->request->has('set3') ||
            !$request->request->has('exId') || !$request->request->has('wId') || !$request->request->has('unW')) {
            return new Response('Missing required parameters', Response::HTTP_BAD_REQUEST);
        }

        // Retrieve parameters from the request
        $set1 = $request->request->get('set1');
        $set2 = $request->request->get('set2');
        $set3 = $request->request->get('set3');
        $exerciseId = $request->request->get('exId');
        $workoutId = $request->request->get('wId');
        $weightInput = $request->request->get('unW');
        $weight = $this->weightStringToFloat($weightInput);
        $unitInfo = null;
        // Check if unitInfo is provided
        if ($request->request->get('unitInfo') != '') {
            $unitInfo = $request->request->get('unitInfo');
        }

        // Validate the exercise and workout IDs
        if (!is_numeric($exerciseId) || !is_numeric($workoutId)) {
            return new Response('Invalid exercise or workout ID', Response::HTTP_BAD_REQUEST);
        }
        $exerciseId = (int) $exerciseId;
        $workoutId = (int) $workoutId;
        if ($exerciseId <= 0 || $workoutId <= 0) {
            return new Response('Exercise and workout IDs must be positive integers', Response::HTTP_BAD_REQUEST);
        }
        // Fetch the Exercise and Workout entities
        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($exerciseId);
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workout = $workoutRepository->find($workoutId);

        // Validate the Exercise and Workout entities
        if (!$exercise || !$workout) {
            return new Response('Invalid exercise or workout ID', Response::HTTP_BAD_REQUEST);
        }

        // set set1, set2, set3 to 1 if isSingleUnit is true
        if ($exercise->isSingleUnit()) {
            $set1 = 1;
            $set2 = 1;
            $set3 = 1;
        } else {
            // Ensure that set1, set2, and set3 are provided
            if ($set1 === null || $set2 === null || $set3 === null) {
                return new Response('Sets must be provided', Response::HTTP_BAD_REQUEST);
            }
            if (!is_numeric($set1) || !is_numeric($set2) || !is_numeric($set3)) {
                return new Response('Sets must be numeric values', Response::HTTP_BAD_REQUEST);
            }
            $set1 = (int) $set1;
            $set2 = (int) $set2;
            $set3 = (int) $set3;
            if ($set1 < 0 || $set2 < 0 || $set3 < 0) {
                return new Response('Sets must be non-negative integers', Response::HTTP_BAD_REQUEST);
            }
        }

        // Validate the sets
        if ($weight < 0) {
            return new Response('Weight must be a non-negative number', Response::HTTP_BAD_REQUEST);
        }

        // Create a new Unit entity
        $unit = new Unit();
        $unit->setSet1($set1);
        $unit->setSet2($set2);
        $unit->setSet3($set3);
        $unit->setWeight($weight);
        $unit->setExercise($exercise);
        $unit->setWorkout($workout);
        if ($unitInfo) {
            $unit->setInfo($unitInfo);
        }

        // Persist the unit to the database
        try {
            $this->entityManager->persist($unit);
            $this->entityManager->flush();

            // Return a JSON response indicating success
            return $this->json([
                'success' => true,
                'message' => 'Unit added successfully',
                'unitId' => $unit->getId(),
            ]);
            // return new Response('Unit added successfully!', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('Error saving unit: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/unit/edit/{id}', name: 'edit_unit')]
    public function edit(Request $request, int $id): Response
    {
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        $unit = $unitRepository->find($id);
        $form = $this->createForm(UnitEditType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit = $form->getData();

            if ($unit->getExercise()->getUsesWeight()) {
                $weightString = $form->get('weight')->getData();
                $weight = $this->weightStringToFloat($weightString);
                $unit->setWeight($weight);
            }

            $this->entityManager->persist($unit);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_workout', ['id' => $unit->getWorkout()->getId()]);
        }

        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function weightStringToFloat(string $weightString) {
        $weight = (float) 0;

        // convert weight input into float; Regex: 
        // ^\s*           → Anfang, optionale Leerzeichen
        // (\d+([.,]\d+)?)→ ein oder mehrere Ziffern, optional gefolgt von Komma/Punkt und weiteren Ziffern
        // \s*            → optionale Leerzeichen
        // (?:kg)?        → optional "kg" (nicht-kapturierend)
        // \s*$           → optionale Leerzeichen bis zum Ende
        if (preg_match('/^\s*(\d+(?:[.,]\d+)?)\s*(?:kg)?\s*$/i', $weightString, $matches)) {
            $normalized = str_replace(',', '.', $matches[1]);
            $weight = (float) $normalized;
        }
        return $weight;
    }
}
