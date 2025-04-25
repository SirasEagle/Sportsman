<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Exercise::class, inversedBy: 'units')]
    private ?Exercise $exercise = null;

    #[ORM\Column]
    private ?int $exerciseId = null;

    #[ORM\ManyToOne(targetEntity: Workout::class, inversedBy: 'units')]
    private ?Workout $workout;

    #[ORM\Column]
    private ?int $workoutId = null;

    #[ORM\Column(nullable: true)]
    private ?int $set1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $set2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $set3 = null;

    /**
     * the weight of dumbbells for example
     */
    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $info = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): void
    {
        $this->exercise = $exercise;
        if ($exercise) {
            $this->exerciseId = $exercise->getId();
        }
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): void
    {
        $this->workout = $workout;
        if ($workout) {
            $this->workoutId = $workout->getId();
        }
    }

    public function getSet1(): ?int
    {
        return $this->set1;
    }

    public function setSet1(?int $set1): self
    {
        $this->set1 = $set1;

        return $this;
    }

    public function getSet2(): ?int
    {
        return $this->set2;
    }

    public function setSet2(?int $set2): self
    {
        $this->set2 = $set2;

        return $this;
    }

    public function getSet3(): ?int
    {
        return $this->set3;
    }

    public function setSet3(?int $set3): self
    {
        $this->set3 = $set3;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }
}
