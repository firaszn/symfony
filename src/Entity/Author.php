<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $username = null;

    #[ORM\Column(length: 55)]
    private ?string $email = null;

    #[ORM\Column]
    private? int $nb_books = 0;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Bookk::class)]
    private Collection $bookks;

    public function __construct()
    {
        $this->bookks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nb_books;
    }

    public function setNbBooks(int $nb_books): static
    {
        $this->nb_books = $nb_books;

        return $this;
    }

    /**
     * @return Collection<int, Bookk>
     */
    public function getBookks(): Collection
    {
        return $this->bookks;
    }

    public function addBookk(Bookk $bookk): static
    {
        if (!$this->bookks->contains($bookk)) {
            $this->bookks->add($bookk);
            $bookk->setAuthor($this);
        }

        return $this;
    }

    public function removeBookk(Bookk $bookk): static
    {
        if ($this->bookks->removeElement($bookk)) {
            if ($bookk->getAuthor() === $this) {
                $bookk->setAuthor(null);
            }
        }

        return $this;
    }
}
