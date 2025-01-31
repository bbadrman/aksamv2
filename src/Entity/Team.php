<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, UniteTravail>
     */
    #[ORM\ManyToMany(targetEntity: UniteTravail::class, mappedBy: 'teams')]
    private Collection $uniteTravails;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'teams')]
    private Collection $users;

    public function __construct()
    {
        $this->uniteTravails = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
     * @return Collection<int, UniteTravail>
     */
    public function getUniteTravails(): Collection
    {
        return $this->uniteTravails;
    }

    public function addUniteTravail(UniteTravail $uniteTravail): static
    {
        if (!$this->uniteTravails->contains($uniteTravail)) {
            $this->uniteTravails->add($uniteTravail);
            $uniteTravail->addTeam($this);
        }

        return $this;
    }

    public function removeUniteTravail(UniteTravail $uniteTravail): static
    {
        if ($this->uniteTravails->removeElement($uniteTravail)) {
            $uniteTravail->removeTeam($this);
        }

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
            $user->addTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTeam($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
