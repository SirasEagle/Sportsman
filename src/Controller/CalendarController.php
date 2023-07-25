<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalendarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {

        $calendarRepository = $this->entityManager->getRepository(Workout::class);
        $workouts = $calendarRepository->findAll();

        return $this->render('calendar/index.html.twig', [
            'workouts' => $workouts,
        ]);
    }
}
