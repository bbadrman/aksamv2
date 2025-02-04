<?php

namespace App\Entity;

use App\Repository\UserHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserHistoryRepository::class)]
class UserHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userHistories')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'userHistories')]
    private ?User $users = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $affectAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getAffectAt(): ?\DateTimeInterface
    {
        return $this->affectAt;
    }

    public function setAffectAt(?\DateTimeInterface $affectAt): static
    {
        $this->affectAt = $affectAt;

        return $this;
    }
}
