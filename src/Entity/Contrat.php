<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSouscrpt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEffet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeContrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imatriclt = null; 

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

    #[ORM\ManyToOne]
    private ?Product $product = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateAchat = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateMec = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $risqueUsage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typePermis = null;

    #[ORM\Column(nullable: true)]
    private ?bool $suspensionPermis = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateSuspension = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifSuspension = null;

    #[ORM\Column(nullable: true)]
    private ?bool $annulationPermis = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateAnnulation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifAnnulation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crmActuel = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $crmRetenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $garanties = null;

    #[ORM\Column(nullable: true)]
    private ?bool $facilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NmbrReglement = null;

    /**
     * @var Collection<int, AntcdentAssurance>
     */
    #[ORM\OneToMany(targetEntity: AntcdentAssurance::class, mappedBy: 'contrat', cascade: ['persist', 'remove'])]
    private Collection $antcdAssure;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NmbrAssure = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?Compartenaire $compagnie = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?Compartenaire $partenaire = null;

    #[ORM\ManyToOne(inversedBy: 'contrats', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    /**
     * @var Collection<int, Sav>
     */
    #[ORM\OneToMany(targetEntity: Sav::class, mappedBy: 'contrat')]
    private Collection $savs;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Document $document = null;

    #[ORM\OneToOne(inversedBy: 'contrat', cascade: ['persist', 'remove'])]
    private ?Payment $payments = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $forceJuridique = null;


    public function __construct()
    {
        $this->antcdAssure = new ArrayCollection();
        $this->savs = new ArrayCollection();
    }

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

    public function getDateSouscrpt(): ?\DateTimeInterface
    {
        return $this->dateSouscrpt;
    }

    public function setDateSouscrpt(?\DateTimeInterface $dateSouscrpt): static
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeImmutable
    {
        return $this->dateAchat;
    }

    public function setDateAchat(?\DateTimeImmutable $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getDateMec(): ?\DateTimeImmutable
    {
        return $this->dateMec;
    }

    public function setDateMec(?\DateTimeImmutable $dateMec): static
    {
        $this->dateMec = $dateMec;

        return $this;
    }

    public function getRisqueUsage(): ?string
    {
        return $this->risqueUsage;
    }

    public function setRisqueUsage(?string $risqueUsage): static
    {
        $this->risqueUsage = $risqueUsage;

        return $this;
    }

    public function getTypePermis(): ?string
    {
        return $this->typePermis;
    }

    public function setTypePermis(?string $typePermis): static
    {
        $this->typePermis = $typePermis;

        return $this;
    }

    public function isSuspensionPermis(): ?bool
    {
        return $this->suspensionPermis;
    }

    public function setSuspensionPermis(?bool $suspensionPermis): static
    {
        $this->suspensionPermis = $suspensionPermis;

        return $this;
    }

    public function getDateSuspension(): ?\DateTimeImmutable
    {
        return $this->dateSuspension;
    }

    public function setDateSuspension(?\DateTimeImmutable $dateSuspension): static
    {
        $this->dateSuspension = $dateSuspension;

        return $this;
    }

    public function getMotifSuspension(): ?string
    {
        return $this->motifSuspension;
    }

    public function setMotifSuspension(?string $motifSuspension): static
    {
        $this->motifSuspension = $motifSuspension;

        return $this;
    }

    public function isAnnulationPermis(): ?bool
    {
        return $this->annulationPermis;
    }

    public function setAnnulationPermis(?bool $annulationPermis): static
    {
        $this->annulationPermis = $annulationPermis;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeImmutable
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(?\DateTimeImmutable $dateAnnulation): static
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): static
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    public function getCrmActuel(): ?string
    {
        return $this->crmActuel;
    }

    public function setCrmActuel(?string $crmActuel): static
    {
        $this->crmActuel = $crmActuel;

        return $this;
    }

    public function getCrmRetenu(): ?string
    {
        return $this->crmRetenu;
    }

    public function setCrmRetenu(?string $crmRetenu): static
    {
        $this->crmRetenu = $crmRetenu;

        return $this;
    }

    public function getGaranties(): ?string
    {
        return $this->garanties;
    }

    public function setGaranties(?string $garanties): static
    {
        $this->garanties = $garanties;

        return $this;
    }



    public function isFacilite(): ?bool
    {
        return $this->facilite;
    }

    public function setFacilite(?bool $facilite): static
    {
        $this->facilite = $facilite;

        return $this;
    }

    public function getNmbrReglement(): ?string
    {
        return $this->NmbrReglement;
    }

    public function setNmbrReglement(?string $NmbrReglement): static
    {
        $this->NmbrReglement = $NmbrReglement;

        return $this;
    }

    /**
     * @return Collection<int, AntcdentAssurance>
     */
    public function getAntcdAssure(): Collection
    {
        return $this->antcdAssure;
    }

    public function addAntcdAssure(AntcdentAssurance $antcdAssure): static
    {
        if (!$this->antcdAssure->contains($antcdAssure)) {
            $this->antcdAssure->add($antcdAssure);
            $antcdAssure->setContrat($this);
        }

        return $this;
    }

    public function removeAntcdAssure(AntcdentAssurance $antcdAssure): static
    {
        if ($this->antcdAssure->removeElement($antcdAssure)) {
            // set the owning side to null (unless already changed)
            if ($antcdAssure->getContrat() === $this) {
                $antcdAssure->setContrat(null);
            }
        }

        return $this;
    }


    public function getNmbrAssure(): ?string
    {
        return $this->NmbrAssure;
    }

    public function setNmbrAssure(?string $NmbrAssure): static
    {
        $this->NmbrAssure = $NmbrAssure;

        return $this;
    }

    public function getCompagnie(): ?Compartenaire
    {
        return $this->compagnie;
    }

    public function setCompagnie(?Compartenaire $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    public function getPartenaire(): ?Compartenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Compartenaire $partenaire): static
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Sav>
     */
    public function getSavs(): Collection
    {
        return $this->savs;
    }

    public function addSav(Sav $sav): static
    {
        if (!$this->savs->contains($sav)) {
            $this->savs->add($sav);
            $sav->setContrat($this);
        }

        return $this;
    }

    public function removeSav(Sav $sav): static
    {
        if ($this->savs->removeElement($sav)) {
            // set the owning side to null (unless already changed)
            if ($sav->getContrat() === $this) {
                $sav->setContrat(null);
            }
        }

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getPayments(): ?Payment
    {
        return $this->payments;
    }

    public function setPayments(?Payment $payments): static
    {
        $this->payments = $payments;

        return $this;
    }

    public function getForceJuridique(): ?string
    {
        return $this->forceJuridique;
    }

    public function setForceJuridique(?string $forceJuridique): static
    {
        $this->forceJuridique = $forceJuridique;

        return $this;
    }
}
