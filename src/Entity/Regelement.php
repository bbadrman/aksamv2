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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $frais = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cotisation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $contreparti = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantReglement1 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantReglement2 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantReglement3 = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateReglement1 = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateReglement2 = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateReglement3 = null;

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

    public function getFrais(): ?string
    {
        return $this->frais;
    }

    public function setFrais(?string $frais): static
    {
        $this->frais = $frais;

        return $this;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(?string $cotisation): static
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function getContreparti(): ?string
    {
        return $this->contreparti;
    }

    public function setContreparti(?string $contreparti): static
    {
        $this->contreparti = $contreparti;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMontantReglement1(): ?string
    {
        return $this->montantReglement1;
    }

    public function setMontantReglement1(?string $montantReglement1): static
    {
        $this->montantReglement1 = $montantReglement1;

        return $this;
    }

    public function getMontantReglement2(): ?string
    {
        return $this->montantReglement2;
    }

    public function setMontantReglement2(?string $montantReglement2): static
    {
        $this->montantReglement2 = $montantReglement2;

        return $this;
    }

    public function getMontantReglement3(): ?string
    {
        return $this->montantReglement3;
    }

    public function setMontantReglement3(?string $montantReglement3): static
    {
        $this->montantReglement3 = $montantReglement3;

        return $this;
    }

    public function getDateReglement1(): ?\DateTimeImmutable
    {
        return $this->dateReglement1;
    }

    public function setDateReglement1(?\DateTimeImmutable $dateReglement1): static
    {
        $this->dateReglement1 = $dateReglement1;

        return $this;
    }

    public function getDateReglement2(): ?\DateTimeImmutable
    {
        return $this->dateReglement2;
    }

    public function setDateReglement2(?\DateTimeImmutable $dateReglement2): static
    {
        $this->dateReglement2 = $dateReglement2;

        return $this;
    }

    public function getDateReglement3(): ?\DateTimeImmutable
    {
        return $this->dateReglement3;
    }

    public function setDateReglement3(?\DateTimeImmutable $dateReglement3): static
    {
        $this->dateReglement3 = $dateReglement3;

        return $this;
    }
}
