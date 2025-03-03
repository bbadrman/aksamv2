<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'products')]
    private Collection $users;

    /**
     * @var Collection<int, Prospect>
     */
    #[ORM\OneToMany(targetEntity: Prospect::class, mappedBy: 'product')]
    private Collection $prospects;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->prospects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addProduct($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeProduct($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNom() ?? '';
    }

    /**
     * @return Collection<int, Prospect>
     */
    public function getProspects(): Collection
    {
        return $this->prospects;
    }

    public function addProspect(Prospect $prospect): static
    {
        if (!$this->prospects->contains($prospect)) {
            $this->prospects->add($prospect);
            $prospect->setProduct($this);
        }

        return $this;
    }

    public function removeProspect(Prospect $prospect): static
    {
        if ($this->prospects->removeElement($prospect)) {
            // set the owning side to null (unless already changed)
            if ($prospect->getProduct() === $this) {
                $prospect->setProduct(null);
            }
        }

        return $this;
    }
}
