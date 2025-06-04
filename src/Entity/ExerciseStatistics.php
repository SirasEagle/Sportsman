<?php

namespace App\Entity;

use App\Repository\ExerciseStatisticsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseStatisticsRepository::class)]
class ExerciseStatistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $weightMedian = null;

    #[ORM\OneToOne(inversedBy: 'exerciseStatistics', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeightMedian(): ?float
    {
        return $this->weightMedian;
    }

    public function setWeightMedian(?float $weightMedian): static
    {
        $this->weightMedian = $weightMedian;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(Exercise $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }
}
