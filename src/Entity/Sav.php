<?php

namespace App\Entity;

use App\Repository\SavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SavRepository::class)]
class Sav
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $natureDemande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $CreatAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifRelance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $relanceAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentTraiter = null;

    #[ORM\ManyToOne(inversedBy: 'savs')]
    private ?Contrat $contrat = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'savs')]
    private Collection $afect;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAjout = null;

    public function __construct()
    {
        $this->afect = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNatureDemande(): ?string
    {
        return $this->natureDemande;
    }

    public function setNatureDemande(?string $natureDemande): static
    {
        $this->natureDemande = $natureDemande;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->CreatAt;
    }

    public function setCreatAt(?\DateTimeInterface $CreatAt): static
    {
        $this->CreatAt = $CreatAt;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

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

    public function getRelanceAt(): ?\DateTimeInterface
    {
        return $this->relanceAt;
    }

    public function setRelanceAt(?\DateTimeInterface $relanceAt): static
    {
        $this->relanceAt = $relanceAt;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCommentTraiter(): ?string
    {
        return $this->commentTraiter;
    }

    public function setCommentTraiter(?string $commentTraiter): static
    {
        $this->commentTraiter = $commentTraiter;

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

    /**
     * @return Collection<int, User>
     */
    public function getAfect(): Collection
    {
        return $this->afect;
    }

    public function addAfect(User $afect): static
    {
        if (!$this->afect->contains($afect)) {
            $this->afect->add($afect);
        }

        return $this;
    }

    public function removeAfect(User $afect): static
    {
        $this->afect->removeElement($afect);

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }
}
