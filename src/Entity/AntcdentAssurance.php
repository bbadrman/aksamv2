<?php

namespace App\Entity;

use App\Repository\AntcdentAssuranceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AntcdentAssuranceRepository::class)]
class AntcdentAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assureur = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatContrat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sinistres = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrSinistre = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brisGlace = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volIncendie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crmAct = null;

    #[ORM\ManyToOne(inversedBy: 'antcdAssure')]
    private ?Contrat $contrat = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrMois = null;



    #[ORM\Column(nullable: true)]
    private ?int $material = null;

    #[ORM\Column(nullable: true)]
    private ?int $corporel = null;

    #[ORM\Column(nullable: true)]
    private ?int $zeresponsable = null;

    #[ORM\Column(nullable: true)]
    private ?int $centResponsable = null;

    #[ORM\Column(nullable: true)]
    private ?int $cinqResponsable = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssureur(): ?string
    {
        return $this->assureur;
    }

    public function setAssureur(?string $assureur): static
    {
        $this->assureur = $assureur;

        return $this;
    }



    public function getEtatContrat(): ?string
    {
        return $this->etatContrat;
    }

    public function setEtatContrat(?string $etatContrat): static
    {
        $this->etatContrat = $etatContrat;

        return $this;
    }

    public function isSinistres(): ?bool
    {
        return $this->sinistres;
    }

    public function setSinistres(?bool $sinistres): static
    {
        $this->sinistres = $sinistres;

        return $this;
    }

    public function getNbrSinistre(): ?int
    {
        return $this->nbrSinistre;
    }

    public function setNbrSinistre(?int $nbrSinistre): static
    {
        $this->nbrSinistre = $nbrSinistre;

        return $this;
    }



    public function getBrisGlace(): ?string
    {
        return $this->brisGlace;
    }

    public function setBrisGlace(?string $brisGlace): static
    {
        $this->brisGlace = $brisGlace;

        return $this;
    }

    public function getVolIncendie(): ?string
    {
        return $this->volIncendie;
    }

    public function setVolIncendie(?string $volIncendie): static
    {
        $this->volIncendie = $volIncendie;

        return $this;
    }

    public function getCrmAct(): ?string
    {
        return $this->crmAct;
    }

    public function setCrmAct(?string $crmAct): static
    {
        $this->crmAct = $crmAct;

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

    public function getNbrMois(): ?int
    {
        return $this->nbrMois;
    }

    public function setNbrMois(?int $nbrMois): static
    {
        $this->nbrMois = $nbrMois;

        return $this;
    }


    public function getMaterial(): ?int
    {
        return $this->material;
    }

    public function setMaterial(?int $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getCorporel(): ?int
    {
        return $this->corporel;
    }

    public function setCorporel(?int $corporel): static
    {
        $this->corporel = $corporel;

        return $this;
    }

    public function getZeresponsable(): ?int
    {
        return $this->zeresponsable;
    }

    public function setZeresponsable(?int $zeresponsable): static
    {
        $this->zeresponsable = $zeresponsable;

        return $this;
    }

    public function getCinqResponsable(): ?string
    {
        return $this->cinqResponsable;
    }

    public function setCinqResponsable(?string $cinqResponsable): static
    {
        $this->cinqResponsable = $cinqResponsable;

        return $this;
    }

    public function getCentResponsable(): ?int
    {
        return $this->centResponsable;
    }

    public function setCentResponsable(?int $centResponsable): static
    {
        $this->centResponsable = $centResponsable;

        return $this;
    }
}
