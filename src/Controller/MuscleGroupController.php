<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Form\MuscleGroupNewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MuscleGroupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/musclegroup', name: 'index_muscle_group')]
    public function index(): Response
    {
        $mgroupRepository = $this->entityManager->getRepository(MuscleGroup::class);
        $anyMg = $mgroupRepository->findOneBy([]);
        if (!$anyMg) {
            // no muscle group, create new muscle group
            return $this->redirectToRoute('new_muscle_group');
        }

        $musclegroups = $mgroupRepository->findAll();

        return $this->render('muscle_group/index.html.twig', [
            'musclegroups' => $musclegroups,
        ]);
    }

    #[Route('/musclegroup/new', name: 'new_muscle_group')]
    public function new(Request $request): Response
    {
        $muscleGroup = new MuscleGroup();
        $form = $this->createForm(MuscleGroupNewType::class, $muscleGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Werte aus dem Formular holen und in das Exercise-Objekt schreiben
            $muscleGroup = $form->getData();

            // Das Exercise-Objekt in der Datenbank persistieren
            $this->entityManager->persist($muscleGroup);
            $this->entityManager->flush();

            // Umleiten zu einer anderen Route, z. B. zur Detailansicht des neuen Exercises
            return $this->redirectToRoute('index_muscle_group');
        }

        return $this->render('muscle_group/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
