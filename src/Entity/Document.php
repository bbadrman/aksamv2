<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bulletinAdhesion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mondatSepa = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conditionGeneral = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $devoirConseil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $permis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kbis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $releveInformation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deviSigne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CarteGriseDefinitive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AtestNonAssure = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AtestNonSinist = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Contrat $contrat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isBulletinAdhesion(): ?string
    {
        return $this->bulletinAdhesion;
    }

    public function setBulletinAdhesion(?string $bulletinAdhesion): static
    {
        $this->bulletinAdhesion = $bulletinAdhesion;

        return $this;
    }

    public function getMondatSepa(): ?string
    {
        return $this->mondatSepa;
    }

    public function setMondatSepa(?string $mondatSepa): static
    {
        $this->mondatSepa = $mondatSepa;

        return $this;
    }

    public function getConditionGeneral(): ?string
    {
        return $this->conditionGeneral;
    }

    public function setConditionGeneral(?string $conditionGeneral): static
    {
        $this->conditionGeneral = $conditionGeneral;

        return $this;
    }

    public function getDevoirConseil(): ?string
    {
        return $this->devoirConseil;
    }

    public function setDevoirConseil(?string $devoirConseil): static
    {
        $this->devoirConseil = $devoirConseil;

        return $this;
    }

    public function getPermis(): ?string
    {
        return $this->permis;
    }

    public function setPermis(?string $permis): static
    {
        $this->permis = $permis;

        return $this;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(?string $kbis): static
    {
        $this->kbis = $kbis;

        return $this;
    }

    public function getReleveInformation(): ?string
    {
        return $this->releveInformation;
    }

    public function setReleveInformation(?string $releveInformation): static
    {
        $this->releveInformation = $releveInformation;

        return $this;
    }

    public function getDeviSigne(): ?string
    {
        return $this->deviSigne;
    }

    public function setDeviSigne(?string $deviSigne): static
    {
        $this->deviSigne = $deviSigne;

        return $this;
    }

    public function getCarteGriseDefinitive(): ?string
    {
        return $this->CarteGriseDefinitive;
    }

    public function setCarteGriseDefinitive(?string $CarteGriseDefinitive): static
    {
        $this->CarteGriseDefinitive = $CarteGriseDefinitive;

        return $this;
    }

    public function getAtestNonAssure(): ?string
    {
        return $this->AtestNonAssure;
    }

    public function setAtestNonAssure(?string $AtestNonAssure): static
    {
        $this->AtestNonAssure = $AtestNonAssure;

        return $this;
    }

    public function getAtestNonSinist(): ?string
    {
        return $this->AtestNonSinist;
    }

    public function setAtestNonSinist(?string $AtestNonSinist): static
    {
        $this->AtestNonSinist = $AtestNonSinist;

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
