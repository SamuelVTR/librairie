<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author_id = null;

    #[ORM\OneToMany(mappedBy: 'book_id', targetEntity: BookCategory::class)]
    private Collection $categorie;

    #[ORM\Column]
    private ?bool $isRented = null;



    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAuthorId(): ?Author
    {
        return $this->author_id;
    }

    public function setAuthorId(?Author $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    /**
     * @return Collection<int, BookCategory>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(BookCategory $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
            $categorie->setBookId($this);
        }

        return $this;
    }

    public function removeCategorie(BookCategory $categorie): self
    {
        if ($this->categorie->removeElement($categorie)) {
            // set the owning side to null (unless already changed)
            if ($categorie->getBookId() === $this) {
                $categorie->setBookId(null);
            }
        }

        return $this;
    }

    public function isIsRented(): ?bool
    {
        return $this->isRented;
    }

    public function setIsRented(bool $isRented): self
    {
        $this->isRented = $isRented;

        return $this;
    }






}
