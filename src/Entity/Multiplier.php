<?php

namespace App\Entity;

use App\Repository\MultiplierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * TODO: generate explaining description
 */

#[ORM\Entity(repositoryClass: MultiplierRepository::class)]
class Multiplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'multiplier', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    #[ORM\Column]
    private ?int $exerciseId = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0], nullable: false)]
    private ?int $addition = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0], nullable: false)]
    private ?int $multiplyBy = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getExerciseId(): ?int
    {
        return $this->exerciseId;
    }

    public function setExerciseId(int $exerciseId): self
    {
        $this->exerciseId = $exerciseId;

        return $this;
    }

    public function getAddition(): ?int
    {
        return $this->addition;
    }

    public function setAddition(int $addition): self
    {
        $this->addition = $addition;

        return $this;
    }

    public function getMultiplyBy(): ?int
    {
        return $this->multiplyBy;
    }

    public function setMultiplyBy(int $multiplyBy): self
    {
        $this->multiplyBy = $multiplyBy;

        return $this;
    }
}
