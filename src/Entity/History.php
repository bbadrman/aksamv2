<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $actionDate = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?Prospect $prospect = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?Team $team = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $actionType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActionDate(): ?\DateTimeInterface
    {
        return $this->actionDate;
    }

    public function setActionDate(?\DateTimeInterface $actionDate): static
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    public function getProspect(): ?Prospect
    {
        return $this->prospect;
    }

    public function setProspect(?Prospect $prospect): static
    {
        $this->prospect = $prospect;

        return $this;
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

    public function getActionType(): ?string
    {
        return $this->actionType;
    }

    public function setActionType(?string $actionType): static
    {
        $this->actionType = $actionType;

        return $this;
    }
}
