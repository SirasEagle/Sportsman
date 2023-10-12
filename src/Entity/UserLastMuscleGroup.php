<?php

namespace App\Entity;

use App\Repository\UserLastMuscleGroupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLastMuscleGroupRepository::class)]
class UserLastMuscleGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'lastMuscleGroups')]
    private ?User $user;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\ManyToOne(targetEntity: MuscleGroup::class, inversedBy: 'lastMuscleGroups')]
    private ?MuscleGroup $muscleGroup;

    #[ORM\Column]
    private ?int $muscleGroupId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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
        }
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

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
}
