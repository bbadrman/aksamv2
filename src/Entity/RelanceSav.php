<?php

namespace App\Entity;

use App\Repository\RelanceSavRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelanceSavRepository::class)]
class RelanceSav
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creatAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'relanceSavs')]
    private ?Sav $sav = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifRelance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creatAt;
    }

    public function setCreatAt(?\DateTimeInterface $creatAt): static
    {
        $this->creatAt = $creatAt;

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

    public function getSav(): ?Sav
    {
        return $this->sav;
    }

    public function setSav(?Sav $sav): static
    {
        $this->sav = $sav;

        return $this;
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
}
