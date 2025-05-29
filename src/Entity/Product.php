<?php

namespace App\Entity;

use App\Config\Category;
use App\Repository\ProductRepository;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Portion::class, mappedBy: 'products')]
    private Collection $portions;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?NutritionalTable $nutritionalTable = null;

    public function __construct()
    {
        $this->category = Category::Meal;
        $this->portions = new ArrayCollection();
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

    /**
     * @return Collection<int, Portion>
     */
    public function getPortions(): Collection
    {
        return $this->portions;
    }

    public function addPortion(Portion $portion): static
    {
        if (!$this->portions->contains($portion)) {
            $this->portions->add($portion);
            $portion->addProduct($this);
        }

        return $this;
    }

    public function removePortion(Portion $portion): static
    {
        if ($this->portions->removeElement($portion)) {
            $portion->removeProduct($this);
        }

        return $this;
    }

    public function getNutritionalTable(): ?NutritionalTable
    {
        return $this->nutritionalTable;
    }

    public function setNutritionalTable(?NutritionalTable $nutritionalTable): static
    {
        // unset the owning side of the relation if necessary
        if ($nutritionalTable === null && $this->nutritionalTable !== null) {
            $this->nutritionalTable->setProduct(null);
        }

        // set the owning side of the relation if necessary
        if ($nutritionalTable !== null && $nutritionalTable->getProduct() !== $this) {
            $nutritionalTable->setProduct($this);
        }

        $this->nutritionalTable = $nutritionalTable;

        return $this;
    }
}
