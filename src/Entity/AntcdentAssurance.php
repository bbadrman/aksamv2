<?php

namespace App\Entity;

use App\Repository\AntcdentAssuranceRepository;
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
    private ?string $typeSinistre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $responsable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pourcentResp = null;

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

    public function getTypeSinistre(): ?string
    {
        return $this->typeSinistre;
    }

    public function setTypeSinistre(?string $typeSinistre): static
    {
        $this->typeSinistre = $typeSinistre;

        return $this;
    }

    public function isResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(?bool $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getpourcentResp(): ?string
    {
        return $this->pourcentResp;
    }

    public function setpourcentResp(?string $pourcentResp): static
    {
        $this->pourcentResp = $pourcentResp;

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
}
