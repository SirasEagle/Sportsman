<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalendarController extends AbstractController
{
    private $entityManager;
    private $loaded = false;
    private $workouts = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function viewCalendar(Request $request, $month = 5): Response
    {
        if (!$this->loaded) {
            $calendarRepository = $this->entityManager->getRepository(Workout::class);
            $this->workouts = $calendarRepository->findAll();
        }

        // Beispiel: Aktuellen Monat und Jahr ermitteln (ersetzen Sie dies durch Ihre eigene Logik):
        $currentDate = new \DateTime();
        $currentMonth = $currentDate->format('m'); // Aktueller Monat
        $currentYear = $currentDate->format('Y'); // Aktuelles Jahr

        return $this->render('calendar/view.html.twig', [
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'workouts' => $this->workouts,
        ]);
    }
}
