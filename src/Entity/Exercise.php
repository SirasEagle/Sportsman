<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Unit::class, mappedBy: 'exercise')]
    private ?Collection $units;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): ?Collection
    {
        return $this->units ?? new ArrayCollection();
    }

    /**
     * Add a unit to the exercise.
     *
     * @param Unit $unit
     */
    public function addUnit(Unit $unit): void
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
            $unit->setExercise($this);
            $unit->setExerciseId($this->getId());
        }
    }

    /**
     * Remove a unit from the exercise.
     *
     * @param Unit $unit
     */
    public function removeUnit(Unit $unit): void
    {
        $this->units->removeElement($unit);
        $unit->setExercise(null);
    }
}
