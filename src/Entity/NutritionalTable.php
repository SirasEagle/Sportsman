<?php

namespace App\Entity;

use App\Repository\NutritionalTableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NutritionalTableRepository::class)]
class NutritionalTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $kcal = null;

    #[ORM\Column(nullable: true)]
    private ?float $fat = null;

    #[ORM\Column(nullable: true)]
    private ?float $saturatedFat = null;

    #[ORM\Column(nullable: true)]
    private ?float $carbohydrates = null;

    #[ORM\Column(nullable: true)]
    private ?float $sugars = null;

    #[ORM\Column(nullable: true)]
    private ?float $protein = null;

    #[ORM\Column(nullable: true)]
    private ?float $salt = null;

    #[ORM\OneToOne(inversedBy: 'nutritionalTable', cascade: ['persist', 'remove'])]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKcal(): ?float
    {
        return $this->kcal;
    }

    public function setKcal(?float $kcal): static
    {
        $this->kcal = $kcal;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): static
    {
        $this->fat = $fat;

        return $this;
    }

    public function getSaturatedFat(): ?float
    {
        return $this->saturatedFat;
    }

    public function setSaturatedFat(?float $saturatedFat): static
    {
        $this->saturatedFat = $saturatedFat;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?float $carbohydrates): static
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getSugars(): ?float
    {
        return $this->sugars;
    }

    public function setSugars(?float $sugars): static
    {
        $this->sugars = $sugars;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(?float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getSalt(): ?float
    {
        return $this->salt;
    }

    public function setSalt(?float $salt): static
    {
        $this->salt = $salt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
