<?php

namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Repository\MuscleGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MuscleGroupRepository::class)]
class MuscleGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $term = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'muscleGroup')]
    private ?Collection $exercises;

    #[ORM\OneToMany(targetEntity: UserLastMuscleGroup::class, mappedBy: 'muscleGroup')]
    private ?Collection $lastMuscleGroups;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->lastMuscleGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;

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
     * @return Collection|Exercise[]
     */
    public function getExercises(): ?Collection
    {
        return $this->exercises ?? new ArrayCollection();
    }

    /**
     * Add an exercise to the muscle-group.
     *
     * @param Exercise $exercise
     */
    public function addExercise(Exercise $exercise): void
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setMuscleGroup($this);
        }
    }

    /**
     * Remove an exercise from the muscle-group.
     *
     * @param Exercise $exercise
     */
    public function removeExercise(Exercise $exercise): void
    {
        $this->exercises->removeElement($exercise);
        $exercise->setMuscleGroup(null);
    }

    /**
     * @return Collection|UserLastMuscleGroup[]
     */
    public function getLastMuscleGroups(): ?Collection
    {
        return $this->lastMuscleGroups ?? new ArrayCollection();
    }

    /**
     * Add an lastMuscleGroup to the muscle-group.
     *
     * @param UserLastMuscleGroup $lastMuscleGroup
     */
    public function addLastMuscleGroup(UserLastMuscleGroup $lastMuscleGroup): void
    {
        if (!$this->lastMuscleGroups->contains($lastMuscleGroup)) {
            $this->lastMuscleGroups->add($lastMuscleGroup);
            $lastMuscleGroup->setMuscleGroup($this);
        }
    }

    /**
     * Remove an lastMuscleGroup from the muscle-group.
     *
     * @param UserLastMuscleGroup $lastMuscleGroup
     */
    public function removeLastMuscleGroup(UserLastMuscleGroup $lastMuscleGroup): void
    {
        $this->lastMuscleGroups->removeElement($lastMuscleGroup);
        $lastMuscleGroup->setMuscleGroup(null);
    }
}
