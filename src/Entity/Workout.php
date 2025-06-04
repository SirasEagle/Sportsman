<?php

namespace App\Entity;

use App\Repository\WorkoutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutRepository::class)]
class Workout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    // Deletes units when the workout is deleted or when a unit is removed by removeUnit()
    #[ORM\OneToMany(targetEntity: Unit::class, mappedBy: 'workout', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Collection $units;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'workouts')]
    private ?User $user;

    #[ORM\Column]
    private ?int $userId = null;

    public function __construct()
    {
        $this->units = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
    
    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

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
     * Add a unit to the workout.
     *
     * @param Unit $unit
     */
    public function addUnit(Unit $unit): void
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
            $unit->setWorkout($this);
        }
    }

    /**
     * Remove a unit from the workout.
     *
     * @param Unit $unit
     */
    public function removeUnit(Unit $unit): void
    {
        $this->units->removeElement($unit);
        $unit->setWorkout(null);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
        if ($user) {
            $this->userId = $user->getId();
        } else {
            $this->userId = null;
        }
    }

}
