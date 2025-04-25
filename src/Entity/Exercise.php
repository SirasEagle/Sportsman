<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $usesWeight = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageLink = null;

    #[ORM\Column(nullable: true)]
    private ?float $median = null;

    #[ORM\OneToMany(targetEntity: Unit::class, mappedBy: 'exercise')]
    private ?Collection $units;

    #[ORM\ManyToOne(targetEntity: MuscleGroup::class, inversedBy: 'exercises')]
    private ?MuscleGroup $muscleGroup;

    #[ORM\Column]
    private ?int $muscleGroupId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNameShort(): ?string
    {
        return substr($this->name, 0, 14) . '...';
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

    public function getUsesWeight(): ?bool
    {
        return $this->usesWeight;
    }

    public function setUsesWeight(?bool $usesWeight): self
    {
        $this->usesWeight = $usesWeight;

        return $this;
    }

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    public function getMedian(): ?float
    {
        return $this->median;
    }

    public function setMedian(?float $median): self
    {
        $this->median = $median;

        return $this;
    }

    public function getMuscleGroup(): ?MuscleGroup
    {
        return $this->muscleGroup;
    }

    public function setMuscleGroup(?MuscleGroup $muscleGroup): void
    {
        $this->muscleGroup = $muscleGroup;
        if ($muscleGroup) {
            $this->muscleGroupId = $muscleGroup->getId();
        }
    }

    public function getMuscleGroupId(): ?int
    {
        return $this->muscleGroupId;
    }

    public function setMuscleGroupId(?int $muscleGroupId): self
    {
        $this->muscleGroupId = $muscleGroupId;

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
