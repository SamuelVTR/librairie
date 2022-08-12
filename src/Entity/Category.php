<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'category_id', targetEntity: BookCategory::class)]
    private Collection $catbook;

    public function __construct()
    {
        $this->catbook = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, BookCategory>
     */
    public function getCatbook(): Collection
    {
        return $this->catbook;
    }

    public function addCatbook(BookCategory $catbook): self
    {
        if (!$this->catbook->contains($catbook)) {
            $this->catbook->add($catbook);
            $catbook->setCategoryId($this);
        }

        return $this;
    }

    public function removeCatbook(BookCategory $catbook): self
    {
        if ($this->catbook->removeElement($catbook)) {
            // set the owning side to null (unless already changed)
            if ($catbook->getCategoryId() === $this) {
                $catbook->setCategoryId(null);
            }
        }

        return $this;
    }
}
