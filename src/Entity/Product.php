<?php

namespace App\Entity;

use App\Config\Category;
use App\Repository\ProductRepository;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', enumType: Category::class, options: ['default' => 'Meal'], nullable: false)]
    private Category $category;

    #[ORM\Column(nullable: true)]
    private ?float $packageSize = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Company $company = null;

    public function __construct()
    {
        $this->category = Category::Meal;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPackageSize(): ?float
    {
        return $this->packageSize;
    }

    public function setPackageSize(?float $packageSize): static
    {
        $this->packageSize = $packageSize;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
