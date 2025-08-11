<?php

namespace App\Service;
use App\Entity\Exercise;
use App\Entity\Unit;
use App\Entity\Workout;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
   private $entityManager;

   public function __construct(EntityManagerInterface $entityManager)
   {
      $this->entityManager = $entityManager;
   }

   /**
    * Calculate the median for each exercise based on its units.
    *
    * @return null
    */
   public function calculateMedian()
   {
      $exerciseRepository = $this->entityManager->getRepository(Exercise::class);
      $exercises = $exerciseRepository->findAll();

      foreach ($exercises as $exercise) {
         if (!$exercise) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Exercise not found');
         }
         
         $unitRepository = $this->entityManager->getRepository(Unit::class);
         $units = $unitRepository->findBy(['exercise' => $exercise]);

         // calculate and set repetition median per exercise
         $median = 0;
         if ($units) {
               foreach ($units as $unit) {
                  $median += $unit->getSet1();
                  $median += $unit->getSet2();
                  $median += $unit->getSet3();
               }
               $median = ($median / (count($units) * 3));
         }
         $exercise->setMedian((float)$median);

         // calculate and set weight median per exercise
         if ($exercise->getUsesWeight()) {
               $weightMedian = 0;
               if ($units) {
                  foreach ($units as $unit) {
                     $weightMedian += $unit->getWeight();
                  }
                  $weightMedian = ($weightMedian / count($units));
               }
               $exercise->getExerciseStatistics()->setWeightMedian((float)$weightMedian);
         }

         $this->entityManager->persist($exercise);
         $this->entityManager->flush();
      }

      return null;
   }

   /**
    * Calculate points for each workout based on its units.
    *
    * @return null
    */
   public function calculatePoints()
   {
      $workoutRepository = $this->entityManager->getRepository(Workout::class);
      $workouts = $workoutRepository->findAll();

      foreach ($workouts as $workout) {
         if (!$workout) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('workout not found');
         }

         $workoutPoints = 0;
         $units = $workout->getUnits();
         foreach ($units as $unit) {
               $temp = 0;
               $unitPoints = 0;

               $unitPoints++; // +1p for making an exercise
               if ($unit->getExercise()->getMultiplier()) {
                  // + fix additional points for making that exercise
                  $unitPoints += $unit->getExercise()->getMultiplier()->getAddition();
               }

               // +1p for each 50% median bases reps
               $temp += $unit->getSet1();
               $temp += $unit->getSet2();
               $temp += $unit->getSet3();
               $temp = (($temp / 3) * 2);
               if ($unit->getExercise()->getMultiplier() && ($unit->getExercise()->getMultiplier()->getMultiplyBy() != 0)) {
                  // multiply by set multiplier
                  $temp = $temp * $unit->getExercise()->getMultiplier()->getMultiplyBy();
               }
               $unitPoints += $temp / $unit->getExercise()->getMedian();

               // multiply everything with weight median if weight gets used
               if ($unit->getExercise()->getUsesWeight()) {
                  if ($unit->getExercise()->getExerciseStatistics() === null) {
                     throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Exercise statistics not found for exercise: ' . $unit->getExercise()->getName());
                  }
                  if ($unit->getExercise()->getExerciseStatistics()->getWeightMedian() === null) {
                     throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Weight median not found for exercise: ' . $unit->getExercise()->getName());
                  }
                  $overallMedian = $unit->getExercise()->getExerciseStatistics()->getWeightMedian();
                  $thisMedian = $unit->getWeight();

                  $unitPoints *= ($thisMedian / $overallMedian);
               }
               $workoutPoints += $unitPoints; // add points of this unit
         }
         $workout->setPoints($workoutPoints);
         $this->entityManager->persist($workout);
      }
      $this->entityManager->flush();

      return null;
   }

}