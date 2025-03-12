<?php

namespace App\Entity;

use App\Repository\RegelementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegelementRepository::class)]
class Regelement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantReglement = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateReglement = null;

    #[ORM\Column(nullable: true)]
    private ?int $transaction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $moyen = null;

    #[ORM\ManyToOne(inversedBy: 'regelement')]
    private ?Contrat $contrat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantReglement(): ?string
    {
        return $this->montantReglement;
    }

    public function setMontantReglement(?string $montantReglement): static
    {
        $this->montantReglement = $montantReglement;

        return $this;
    }

    public function getDateReglement(): ?\DateTimeImmutable
    {
        return $this->dateReglement;
    }

    public function setDateReglement(?\DateTimeImmutable $dateReglement): static
    {
        $this->dateReglement = $dateReglement;

        return $this;
    }

    public function getTransaction(): ?int
    {
        return $this->transaction;
    }

    public function setTransaction(?int $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getMoyen(): ?string
    {
        return $this->moyen;
    }

    public function setMoyen(?string $moyen): static
    {
        $this->moyen = $moyen;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }
}
