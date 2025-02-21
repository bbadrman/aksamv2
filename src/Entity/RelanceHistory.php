<?php

namespace App\Entity;

use App\Repository\RelanceHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelanceHistoryRepository::class)]
class RelanceHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifRelance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $relancedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'relanceHistories')]
    private ?Prospect $prospect = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotifRelance(): ?string
    {
        return $this->motifRelance;
    }

    public function setMotifRelance(?string $motifRelance): static
    {
        $this->motifRelance = $motifRelance;

        return $this;
    }

    public function getRelancedAt(): ?\DateTimeImmutable
    {
        return $this->relancedAt;
    }

    public function setRelancedAt(?\DateTimeImmutable $relancedAt): static
    {
        $this->relancedAt = $relancedAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

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
}
