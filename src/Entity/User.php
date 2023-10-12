<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\OneToMany(targetEntity: Workout::class, mappedBy: 'user')]
    private ?Collection $workouts;

    #[ORM\OneToMany(targetEntity: UserLastMuscleGroup::class, mappedBy: 'user')]
    private ?Collection $lastMuscleGroups;

    public function __construct()
    {
        $this->workouts = new ArrayCollection();
        $this->lastMuscleGroups = new ArrayCollection();
    }

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Workout[]
     */
    public function getWorkouts(): ?Collection
    {
        return $this->workouts ?? new ArrayCollection();
    }

    /**
     * Add a workout to the user.
     *
     * @param Workout $workout
     */
    public function addWorkout(Workout $workout): void
    {
        if (!$this->workouts->contains($workout)) {
            $this->workouts->add($workout);
            $workout->setUser($this);
        }
    }

    /**
     * Remove a workout from the user.
     *
     * @param Workout $workout
     */
    public function removeWorkout(Workout $workout): void
    {
        $this->workouts->removeElement($workout);
        $workout->setUser(null);
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
            $lastMuscleGroup->setUser($this);
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
        $lastMuscleGroup->setUser(null);
    }
}
