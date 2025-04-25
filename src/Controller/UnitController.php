<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UnitEditType;
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

    #[Route('/unit/edit/{id}', name: 'edit_unit')]
    public function edit(Request $request, int $id): Response
    {
        $unitRepository = $this->entityManager->getRepository(Unit::class);
        $unit = $unitRepository->find($id);
        $form = $this->createForm(UnitEditType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit = $form->getData();

            $this->entityManager->persist($unit);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_workout', ['id' => $unit->getWorkout()->getId()]);
        }

        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/save-unit", name: "save_unit", methods: "POST")]
    public function saveUnit(Request $request): Response
    {
        $set1 = $request->request->get('set1');
        $set2 = $request->request->get('set2');
        $set3 = $request->request->get('set3');
        $exerciseId = $request->request->get('exId');
        $workoutId = $request->request->get('wId');
        $weightInput = $request->request->get('unW');
        $weight = (float) 0;
        $unitInfo = null;
        if ($request->request->get('unitInfo') != '') {
            $unitInfo = $request->request->get('unitInfo');
        }

        $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
        $exercise = $exerciseRepository->find($exerciseId);
        $workoutRepository = $this->entityManager->getRepository(Workout::class);
        $workout = $workoutRepository->find($workoutId);

        // convert weight input into float; Regex: 
        // ^\s*           → Anfang, optionale Leerzeichen
        // (\d+([.,]\d+)?)→ ein oder mehrere Ziffern, optional gefolgt von Komma/Punkt und weiteren Ziffern
        // \s*            → optionale Leerzeichen
        // (?:kg)?        → optional "kg" (nicht-kapturierend)
        // \s*$           → optionale Leerzeichen bis zum Ende
        if (preg_match('/^\s*(\d+(?:[.,]\d+)?)\s*(?:kg)?\s*$/i', $weightInput, $matches)) {
            $normalized = str_replace(',', '.', $matches[1]);
            $weight = (float) $normalized;
        }

        $unit = new Unit();
        $unit->setSet1($set1);
        $unit->setSet2($set2);
        $unit->setSet3($set3);
        $unit->setWeight($weight);
        $unit->setExercise($exercise);
        $unit->setWorkout($workout);
        $unit->setInfo($unitInfo);
        // print_r($unit);

        $this->entityManager->persist($unit);
        $this->entityManager->flush();

        return new Response('Data saved successfully!', Response::HTTP_OK);
    }
}
