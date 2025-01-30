<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raisonSociale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateSouscrpt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEffet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeContrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imatriclt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partenaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compagnie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $formule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fraction = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $frais = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeConducteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conducteur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePermis = null;



    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cotisation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $acompte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $firstReglement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $secondReglement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jourPrelvm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeProduct = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isModif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePreleveAcompte = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getDateSouscrpt(): ?string
    {
        return $this->dateSouscrpt;
    }

    public function setDateSouscrpt(?string $dateSouscrpt): static
    {
        $this->dateSouscrpt = $dateSouscrpt;

        return $this;
    }

    public function getDateEffet(): ?\DateTimeInterface
    {
        return $this->dateEffet;
    }

    public function setDateEffet(?\DateTimeInterface $dateEffet): static
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): static
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    public function getImatriclt(): ?string
    {
        return $this->imatriclt;
    }

    public function setImatriclt(?string $imatriclt): static
    {
        $this->imatriclt = $imatriclt;

        return $this;
    }

    public function getPartenaire(): ?string
    {
        return $this->partenaire;
    }

    public function setPartenaire(?string $partenaire): static
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(?string $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    public function getFormule(): ?string
    {
        return $this->formule;
    }

    public function setFormule(?string $formule): static
    {
        $this->formule = $formule;

        return $this;
    }

    public function getFraction(): ?string
    {
        return $this->fraction;
    }

    public function setFraction(?string $fraction): static
    {
        $this->fraction = $fraction;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeConducteur(): ?string
    {
        return $this->typeConducteur;
    }

    public function setTypeConducteur(?string $typeConducteur): static
    {
        $this->typeConducteur = $typeConducteur;

        return $this;
    }

    public function getConducteur(): ?string
    {
        return $this->conducteur;
    }

    public function setConducteur(?string $conducteur): static
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    public function getDatePermis(): ?\DateTimeInterface
    {
        return $this->datePermis;
    }

    public function setDatePermis(?\DateTimeInterface $datePermis): static
    {
        $this->datePermis = $datePermis;

        return $this;
    }



    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getAcompte(): ?string
    {
        return $this->acompte;
    }

    public function setAcompte(?string $acompte): static
    {
        $this->acompte = $acompte;

        return $this;
    }

    public function getFirstReglement(): ?string
    {
        return $this->firstReglement;
    }

    public function setFirstReglement(string $firstReglement): static
    {
        $this->firstReglement = $firstReglement;

        return $this;
    }

    public function getSecondReglement(): ?string
    {
        return $this->secondReglement;
    }

    public function setSecondReglement(?string $secondReglement): static
    {
        $this->secondReglement = $secondReglement;

        return $this;
    }

    public function getJourPrelvm(): ?string
    {
        return $this->jourPrelvm;
    }

    public function setJourPrelvm(?string $jourPrelvm): static
    {
        $this->jourPrelvm = $jourPrelvm;

        return $this;
    }

    public function getTypeProduct(): ?string
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(?string $typeProduct): static
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }

    public function isModif(): ?bool
    {
        return $this->isModif;
    }

    public function setModif(?bool $isModif): static
    {
        $this->isModif = $isModif;

        return $this;
    }

    public function getDatePreleveAcompte(): ?\DateTimeInterface
    {
        return $this->datePreleveAcompte;
    }

    public function setDatePreleveAcompte(?\DateTimeInterface $datePreleveAcompte): static
    {
        $this->datePreleveAcompte = $datePreleveAcompte;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
